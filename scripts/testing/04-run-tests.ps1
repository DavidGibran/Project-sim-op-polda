[CmdletBinding()]
param(
    [Parameter(Mandatory = $false)]
    [string] $TestPath,

    [Parameter(Mandatory = $false)]
    [string] $Filter,

    [switch] $StopOnFailure
)

$ErrorActionPreference = 'Stop'
$script:NativeExitCode = 0

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

function Invoke-NativeWithLog {
    param(
        [string] $Executable,
        [string[]] $Arguments,
        [string] $LogPath
    )

    Write-Host "`n> $Executable $($Arguments -join ' ')" -ForegroundColor Cyan
    & $Executable @Arguments 2>&1 | Tee-Object -FilePath $LogPath
    $script:NativeExitCode = $LASTEXITCODE
}

function Show-TestSummary {
    param([string] $LogPath)

    $text = Get-Content -LiteralPath $LogPath -Raw
    $summaryLine = ([regex]::Matches($text, 'Tests:\s*[^\r\n]*(?:passed|failed)[^\r\n]*') | Select-Object -Last 1).Value
    $durationMatch = [regex]::Match($text, 'Duration:\s*([0-9.]+s)')

    $passed = if (-not $summaryLine) { '<tidak tersedia>' } elseif ($summaryLine -match '(\d+)\s+passed') { $Matches[1] } else { '0' }
    $failed = if (-not $summaryLine) { '<tidak tersedia>' } elseif ($summaryLine -match '(\d+)\s+failed') { $Matches[1] } else { '0' }
    $skipped = if (-not $summaryLine) { '<tidak tersedia>' } elseif ($summaryLine -match '(\d+)\s+skipped') { $Matches[1] } else { '0' }
    $assertions = if (-not $summaryLine) { '<tidak tersedia>' } elseif ($summaryLine -match '(\d+)\s+assertions?') { $Matches[1] } else { '0' }
    $duration = if ($durationMatch.Success) { $durationMatch.Groups[1].Value } else { '<tidak tersedia>' }

    Write-Host "`n=== Ringkasan Test ===" -ForegroundColor Green
    Write-Host "Passed     : $passed"
    Write-Host "Failed     : $failed"
    Write-Host "Skipped    : $skipped"
    Write-Host "Assertions : $assertions"
    Write-Host "Duration   : $duration"
    Write-Host "Output     : $LogPath"
}

try {
    $projectRoot = Assert-ProjectRoot
    $resultDirectory = Join-Path $projectRoot 'storage\app\testing-results'
    New-Item -ItemType Directory -Path $resultDirectory -Force | Out-Null

    $timestamp = Get-Date -Format 'yyyyMMdd-HHmmss-fff'
    $textLog = Join-Path $resultDirectory "tests-$timestamp.txt"
    $artisanArguments = @('artisan', 'test', '--without-tty', '--no-ansi')

    if ($TestPath) { $artisanArguments += $TestPath }
    if ($Filter) { $artisanArguments += "--filter=$Filter" }
    if ($StopOnFailure) { $artisanArguments += '--stop-on-failure' }
    $artisanArguments += @('--testdox', '--colors=never')

    Invoke-NativeWithLog -Executable 'php' -Arguments $artisanArguments -LogPath $textLog
    $testExitCode = $script:NativeExitCode
    Show-TestSummary -LogPath $textLog
    Write-Host "Exit Code  : $testExitCode"
    if ($testExitCode -ne 0) {
        throw "Automated test gagal dengan exit code $testExitCode."
    }

    exit 0
} catch {
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
