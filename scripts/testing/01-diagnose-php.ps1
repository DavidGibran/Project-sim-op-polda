[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'

function Assert-ProjectRoot {
    $expectedRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..\..'))
    $currentRoot = [System.IO.Path]::GetFullPath((Get-Location).Path)

    if ($currentRoot.TrimEnd('\') -ne $expectedRoot.TrimEnd('\')) {
        throw "Script harus dijalankan dari root project: $expectedRoot"
    }

    if (-not (Test-Path -LiteralPath (Join-Path $currentRoot 'artisan') -PathType Leaf)) {
        throw "File artisan tidak ditemukan di root project: $currentRoot"
    }

    return $currentRoot
}

function Invoke-CheckedCommand {
    param(
        [Parameter(Mandatory = $true)]
        [string] $Label,

        [Parameter(Mandatory = $true)]
        [scriptblock] $Command
    )

    Write-Host "`n=== $Label ===" -ForegroundColor Cyan
    $output = @(& $Command 2>&1 | ForEach-Object { $_.ToString() })
    $exitCode = $LASTEXITCODE
    $output | ForEach-Object { Write-Host $_ }

    if ($exitCode -ne 0) {
        throw "$Label gagal dengan exit code $exitCode."
    }

    return $output
}

function Get-PhpInfoValue {
    param(
        [string[]] $PhpInfo,
        [string] $Name
    )

    $line = $PhpInfo | Where-Object { $_ -match "^$([regex]::Escape($Name))\s*=>" } | Select-Object -First 1
    if (-not $line) {
        return '<tidak ditemukan>'
    }

    return (($line -split '=>', 3)[1]).Trim()
}

try {
    $projectRoot = Assert-ProjectRoot
    $resultDirectory = Join-Path $projectRoot 'storage\app\testing-results'
    New-Item -ItemType Directory -Path $resultDirectory -Force | Out-Null

    $wherePhp = Invoke-CheckedCommand -Label 'where.exe php' -Command { where.exe php }
    Invoke-CheckedCommand -Label 'php -v' -Command { php -v } | Out-Null
    Invoke-CheckedCommand -Label 'php --ini' -Command { php --ini } | Out-Null

    Write-Host "`n=== Menyimpan php -i ===" -ForegroundColor Cyan
    $phpInfo = @(& php -i 2>&1 | ForEach-Object { $_.ToString() })
    if ($LASTEXITCODE -ne 0) {
        throw "php -i gagal dengan exit code $LASTEXITCODE."
    }

    $phpInfoPath = Join-Path $resultDirectory 'phpinfo-cli.txt'
    [System.IO.File]::WriteAllLines($phpInfoPath, $phpInfo, (New-Object System.Text.UTF8Encoding($false)))

    $architecture = Get-PhpInfoValue -PhpInfo $phpInfo -Name 'Architecture'
    $threadSafety = Get-PhpInfoValue -PhpInfo $phpInfo -Name 'Thread Safety'
    $compiler = Get-PhpInfoValue -PhpInfo $phpInfo -Name 'Compiler'
    $extensionDirectory = Get-PhpInfoValue -PhpInfo $phpInfo -Name 'extension_dir'
    $phpVersion = ((& php -r 'echo PHP_VERSION;') | Out-String).Trim()
    if ($LASTEXITCODE -ne 0) {
        throw 'Gagal membaca PHP_VERSION.'
    }

    $phpBinary = ((& php -r 'echo PHP_BINARY;') | Out-String).Trim()
    if ($LASTEXITCODE -ne 0) {
        throw 'Gagal membaca PHP_BINARY.'
    }

    $modules = @(& php -m 2>&1 | ForEach-Object { $_.ToString() })
    if ($LASTEXITCODE -ne 0) {
        throw 'Gagal membaca daftar extension PHP.'
    }

    $coverageDrivers = @($modules | Where-Object { $_ -match '^(xdebug|pcov)$' })
    $threadBuild = if ($threadSafety -eq 'disabled') { 'NTS' } else { 'TS' }
    $driverText = if ($coverageDrivers.Count -eq 0) { 'Tidak ada' } else { $coverageDrivers -join ', ' }

    Write-Host "`n=== Ringkasan PHP CLI ===" -ForegroundColor Green
    Write-Host "PHP pada PATH     : $($wherePhp -join ', ')"
    Write-Host "PHP_BINARY         : $phpBinary"
    Write-Host "PHP_VERSION        : $phpVersion"
    Write-Host "Architecture       : $architecture"
    Write-Host "Thread Safety      : $threadSafety ($threadBuild)"
    Write-Host "Compiler           : $compiler"
    Write-Host "extension_dir      : $extensionDirectory"
    Write-Host "Xdebug/PCOV        : $driverText"
    Write-Host "phpinfo CLI        : $phpInfoPath"

    Write-Host "`nCatatan strategi testing:" -ForegroundColor Yellow
    Write-Host 'Automated test project menggunakan testing bawaan Laravel tanpa code coverage otomatis.'
    Write-Host 'Informasi Xdebug/PCOV hanya bersifat diagnosis opsional dan tidak dibutuhkan oleh suite utama.'
    exit 0
} catch {
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
