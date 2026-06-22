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
        throw 'File artisan tidak ditemukan.'
    }

    if (-not (Test-Path -LiteralPath (Join-Path $currentRoot '.env.testing') -PathType Leaf)) {
        throw 'File .env.testing tidak ditemukan.'
    }
}

function Invoke-CheckedPhpArtisan {
    param([string[]] $Arguments)

    Write-Host "`n> php artisan $($Arguments -join ' ')" -ForegroundColor Cyan
    & php artisan @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Command artisan gagal dengan exit code $LASTEXITCODE."
    }
}

try {
    Assert-ProjectRoot

    Write-Host 'Memeriksa koneksi environment testing tanpa mengubah database...' -ForegroundColor Cyan
    $probeCode = "echo 'DB_DRIVER=', config('database.default'), PHP_EOL; echo 'DB_DATABASE=', DB::connection()->getDatabaseName(), PHP_EOL;"
    $probeOutput = @(& php artisan tinker --env=testing --execute=$probeCode 2>&1 | ForEach-Object { $_.ToString() })
    $probeOutput | ForEach-Object { Write-Host $_ }
    if ($LASTEXITCODE -ne 0) {
        throw 'Tidak dapat membaca koneksi database testing.'
    }

    $probeText = $probeOutput -join "`n"
    $driverMatch = [regex]::Match($probeText, 'DB_DRIVER=([^\r\n]+)')
    $databaseMatch = [regex]::Match($probeText, 'DB_DATABASE=([^\r\n]+)')
    if (-not $driverMatch.Success -or -not $databaseMatch.Success) {
        throw 'Output pemeriksaan database tidak dapat diparsing.'
    }

    $driver = $driverMatch.Groups[1].Value.Trim()
    $database = $databaseMatch.Groups[1].Value.Trim()
    if ($driver -cne 'mysql') {
        throw "Database default bukan mysql: $driver"
    }

    if ($database -cne 'sim_op_polda_testing') {
        throw "Database aktif bukan sim_op_polda_testing: $database"
    }

    Write-Host "Database testing aman: $driver / $database" -ForegroundColor Green
    Invoke-CheckedPhpArtisan -Arguments @('optimize:clear', '--env=testing', '--no-ansi')
    Invoke-CheckedPhpArtisan -Arguments @('migrate:status', '--env=testing', '--no-ansi')

    Write-Host "`nVerifikasi environment testing selesai. migrate:fresh tidak dijalankan." -ForegroundColor Green
    exit 0
} catch {
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host 'Proses dihentikan tanpa menjalankan migrate:fresh.' -ForegroundColor Yellow
    exit 1
}
