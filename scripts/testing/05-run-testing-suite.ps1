[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$transcriptStarted = $false

function Assert-ProjectRoot {
    $expectedRoot = [System.IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..\..'))
    $currentRoot = [System.IO.Path]::GetFullPath((Get-Location).Path)

    if ($currentRoot.TrimEnd('\') -ne $expectedRoot.TrimEnd('\')) {
        throw "Script harus dijalankan dari root project: $expectedRoot"
    }

    if (-not (Test-Path -LiteralPath (Join-Path $currentRoot 'artisan') -PathType Leaf)) {
        throw 'File artisan tidak ditemukan.'
    }

    return $currentRoot
}

function Invoke-ScriptChecked {
    param(
        [string] $Label,
        [string] $Path,
        [hashtable] $Parameters = @{}
    )

    Write-Host "`n=== $Label ===" -ForegroundColor Cyan
    & $Path @Parameters
    if ($LASTEXITCODE -ne 0) {
        throw "$Label gagal dengan exit code $LASTEXITCODE."
    }
}

try {
    $projectRoot = Assert-ProjectRoot
    $resultDirectory = Join-Path $projectRoot 'storage\app\testing-results'
    New-Item -ItemType Directory -Path $resultDirectory -Force | Out-Null

    $timestamp = Get-Date -Format 'yyyyMMdd-HHmmss-fff'
    $suiteLog = Join-Path $resultDirectory "testing-suite-$timestamp.txt"
    $startedAt = Get-Date
    Start-Transcript -LiteralPath $suiteLog -Force | Out-Null
    $transcriptStarted = $true

    $verifyScript = Join-Path $PSScriptRoot '03-verify-testing-environment.ps1'
    $testScript = Join-Path $PSScriptRoot '04-run-tests.ps1'

    Invoke-ScriptChecked -Label 'Verifikasi environment testing' -Path $verifyScript
    Invoke-ScriptChecked -Label 'Authentication starter-kit' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Auth' }
    Invoke-ScriptChecked -Label 'Login universal' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Authentication' }
    Invoke-ScriptChecked -Label 'Middleware' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Middleware' }
    Invoke-ScriptChecked -Label 'Penugasan' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Penugasan' }
    Invoke-ScriptChecked -Label 'Otorisasi penugasan' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Authorization' }
    Invoke-ScriptChecked -Label 'Perjalanan' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Perjalanan' }
    Invoke-ScriptChecked -Label 'Laporan kerusakan' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\LaporanKerusakan' }
    Invoke-ScriptChecked -Label 'Perbaikan' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Perbaikan' }
    Invoke-ScriptChecked -Label 'Odometer' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Odometer' }
    Invoke-ScriptChecked -Label 'Export laporan' -Path $testScript -Parameters @{ TestPath = 'tests\Feature\Laporan\ExportLaporanTest.php' }
    Invoke-ScriptChecked -Label 'Seluruh automated test' -Path $testScript

    Stop-Transcript | Out-Null
    $transcriptStarted = $false

    Write-Host "`n=== File hasil suite ===" -ForegroundColor Green
    $results = Get-ChildItem -LiteralPath $resultDirectory -File -Force |
        Where-Object { $_.LastWriteTime -ge $startedAt } |
        Sort-Object FullName

    if (-not $results) {
        throw 'Suite selesai tetapi tidak ada file hasil yang ditemukan.'
    }

    $results | ForEach-Object { Write-Host $_.FullName }
    Write-Host "Suite transcript: $suiteLog"
    Write-Host 'Testing suite selesai tanpa Xdebug, PCOV, coverage, atau perubahan source code.' -ForegroundColor Green
    exit 0
} catch {
    if ($transcriptStarted) {
        try { Stop-Transcript | Out-Null } catch { }
    }

    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
