[CmdletBinding()]
param(
    [Parameter(Mandatory = $true)]
    [string] $DllPath,

    [Parameter(Mandatory = $false)]
    [string] $PhpIniPath
)

# OPTIONAL: tidak digunakan oleh testing suite utama. Jalankan hanya jika
# strategi code coverage diaktifkan kembali secara eksplisit.
$ErrorActionPreference = 'Stop'
$iniChanged = $false
$dllChanged = $false
$targetDllExisted = $false
$iniBackup = $null
$dllBackup = $null
$targetDll = $null

function Assert-ProjectRoot {
    $expectedRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..\..'))
    $currentRoot = [System.IO.Path]::GetFullPath((Get-Location).Path)

    if ($currentRoot.TrimEnd('\') -ne $expectedRoot.TrimEnd('\')) {
        throw "Script harus dijalankan dari root project: $expectedRoot"
    }

    if (-not (Test-Path -LiteralPath (Join-Path $currentRoot 'artisan') -PathType Leaf)) {
        throw 'File artisan tidak ditemukan.'
    }
}

function Get-ActivePhpIni {
    param([string] $PhpBinary)

    $iniOutput = @(& $PhpBinary --ini 2>&1 | ForEach-Object { $_.ToString() })
    if ($LASTEXITCODE -ne 0) {
        throw 'Gagal membaca php.ini aktif.'
    }

    $loadedLine = $iniOutput | Where-Object { $_ -match '^Loaded Configuration File:' } | Select-Object -First 1
    $loadedPath = ($loadedLine -replace '^Loaded Configuration File:\s*', '').Trim()
    if (-not $loadedPath -or $loadedPath -eq '(none)') {
        throw 'PHP CLI tidak memuat php.ini.'
    }

    return [System.IO.Path]::GetFullPath($loadedPath)
}

function Remove-ExistingXdebugConfiguration {
    param([string[]] $Lines)

    $result = New-Object System.Collections.Generic.List[string]
    $insideXdebugSection = $false

    foreach ($line in $Lines) {
        if ($line -match '^\s*\[([^]]+)\]\s*$') {
            $sectionName = $Matches[1]
            if ($sectionName -ieq 'Xdebug') {
                $insideXdebugSection = $true
                continue
            }

            $insideXdebugSection = $false
        }

        if ($insideXdebugSection) {
            continue
        }

        if ($line -match '^\s*(zend_extension\s*=.*xdebug|xdebug\.)') {
            continue
        }

        $result.Add($line)
    }

    return $result.ToArray()
}

function Restore-Installation {
    if ($iniChanged -and $iniBackup -and (Test-Path -LiteralPath $iniBackup)) {
        Copy-Item -LiteralPath $iniBackup -Destination $activeIni -Force
    }

    if ($dllChanged -and $targetDll) {
        if ($targetDllExisted -and $dllBackup -and (Test-Path -LiteralPath $dllBackup)) {
            Copy-Item -LiteralPath $dllBackup -Destination $targetDll -Force
        } elseif (-not $targetDllExisted -and (Test-Path -LiteralPath $targetDll)) {
            Remove-Item -LiteralPath $targetDll -Force
        }
    }
}

try {
    Write-Warning 'Script Xdebug bersifat opsional dan tidak digunakan oleh strategi testing Laravel saat ini.'
    Assert-ProjectRoot

    $sourceDll = [System.IO.Path]::GetFullPath($DllPath)
    if (-not (Test-Path -LiteralPath $sourceDll -PathType Leaf)) {
        throw "DLL Xdebug tidak ditemukan: $sourceDll"
    }

    $phpCommand = Get-Command php.exe -CommandType Application -ErrorAction Stop
    $phpBinary = [System.IO.Path]::GetFullPath($phpCommand.Source)
    $phpDirectory = Split-Path -Parent $phpBinary
    $activeIni = Get-ActivePhpIni -PhpBinary $phpBinary
    $selectedIni = if ($PhpIniPath) { [System.IO.Path]::GetFullPath($PhpIniPath) } else { $activeIni }

    if ($selectedIni -ine $activeIni) {
        throw "PhpIniPath bukan php.ini aktif milik PHP CLI. Aktif: $activeIni"
    }

    if ((Split-Path -Parent $activeIni).TrimEnd('\') -ine $phpDirectory.TrimEnd('\')) {
        throw "php.ini aktif tidak berada pada direktori PHP CLI $phpDirectory. Instalasi dihentikan."
    }

    Write-Host 'Menguji kompatibilitas DLL dengan PHP CLI aktif...' -ForegroundColor Cyan
    $preflight = @(& $phpBinary -n -d "zend_extension=$sourceDll" -r 'if (!extension_loaded("xdebug")) { fwrite(STDERR, "Xdebug tidak dapat dimuat.\n"); exit(10); } echo PHP_VERSION, " | Xdebug ", phpversion("xdebug"), PHP_EOL;' 2>&1 | ForEach-Object { $_.ToString() })
    $preflight | ForEach-Object { Write-Host $_ }
    if ($LASTEXITCODE -ne 0) {
        throw 'DLL tidak kompatibel dengan PHP CLI aktif. Gunakan rekomendasi Xdebug Installation Wizard.'
    }

    $extensionDirectoryValue = ((& $phpBinary -r 'echo ini_get("extension_dir");') | Out-String).Trim()
    if ($LASTEXITCODE -ne 0 -or -not $extensionDirectoryValue) {
        throw 'Gagal membaca extension_dir.'
    }

    $extensionDirectory = if ([System.IO.Path]::IsPathRooted($extensionDirectoryValue)) {
        [System.IO.Path]::GetFullPath($extensionDirectoryValue)
    } else {
        [System.IO.Path]::GetFullPath((Join-Path $phpDirectory $extensionDirectoryValue))
    }

    if (-not (Test-Path -LiteralPath $extensionDirectory -PathType Container)) {
        throw "extension_dir tidak ditemukan: $extensionDirectory"
    }

    $timestamp = Get-Date -Format 'yyyyMMdd-HHmmss-fff'
    $iniBackup = "$activeIni.bak-$timestamp"
    Copy-Item -LiteralPath $activeIni -Destination $iniBackup
    Write-Host "Backup php.ini: $iniBackup" -ForegroundColor Green

    $targetDll = Join-Path $extensionDirectory 'php_xdebug.dll'
    $targetDllExisted = Test-Path -LiteralPath $targetDll -PathType Leaf
    if ($targetDllExisted) {
        $dllBackup = "$targetDll.bak-$timestamp"
        Copy-Item -LiteralPath $targetDll -Destination $dllBackup
    }

    if ($sourceDll -ine $targetDll) {
        Copy-Item -LiteralPath $sourceDll -Destination $targetDll -Force
    }
    $dllChanged = $true

    $existingLines = [System.IO.File]::ReadAllLines($activeIni)
    $cleanLines = Remove-ExistingXdebugConfiguration -Lines $existingLines
    $newLines = New-Object System.Collections.Generic.List[string]
    $newLines.AddRange([string[]] $cleanLines)
    $newLines.Add('')
    $newLines.Add('[Xdebug]')
    $newLines.Add("zend_extension=`"$targetDll`"")
    $newLines.Add('xdebug.mode=coverage')
    [System.IO.File]::WriteAllLines($activeIni, $newLines.ToArray(), (New-Object System.Text.UTF8Encoding($false)))
    $iniChanged = $true

    Write-Host "`n=== php -v ===" -ForegroundColor Cyan
    & $phpBinary -v
    if ($LASTEXITCODE -ne 0) { throw 'php -v gagal setelah instalasi.' }

    Write-Host "`n=== php -m ===" -ForegroundColor Cyan
    & $phpBinary -m
    if ($LASTEXITCODE -ne 0) { throw 'php -m gagal setelah instalasi.' }

    Write-Host "`n=== php --ri xdebug ===" -ForegroundColor Cyan
    & $phpBinary --ri xdebug
    if ($LASTEXITCODE -ne 0) { throw 'php --ri xdebug gagal.' }

    $mode = ((& $phpBinary -r 'echo ini_get("xdebug.mode");') | Out-String).Trim()
    if ($LASTEXITCODE -ne 0 -or (($mode -split ',') -notcontains 'coverage')) {
        throw "xdebug.mode tidak memuat coverage. Nilai aktif: $mode"
    }

    Write-Host "`nXdebug berhasil dipasang untuk PHP CLI." -ForegroundColor Green
    Write-Host "PHP binary   : $phpBinary"
    Write-Host "php.ini      : $activeIni"
    Write-Host "DLL          : $targetDll"
    Write-Host "xdebug.mode  : $mode"
    exit 0
} catch {
    $message = $_.Exception.Message
    try {
        Restore-Installation
    } catch {
        Write-Host "PERINGATAN: rollback juga gagal: $($_.Exception.Message)" -ForegroundColor Red
    }

    Write-Host "ERROR: $message" -ForegroundColor Red
    if ($iniChanged) {
        Write-Host 'Perubahan instalasi telah dikembalikan dari backup.' -ForegroundColor Yellow
    }
    exit 1
}
