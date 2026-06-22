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

    return $currentRoot
}

function Get-ParsedValue {
    param(
        [string] $Text,
        [string] $Pattern
    )

    $match = [regex]::Match($Text, $Pattern)
    if ($match.Success) {
        return $match.Groups[1].Value
    }

    return 'perlu diisi manual'
}

function Get-ModuleName {
    param([string] $ClassPath)

    if ($ClassPath -match '^Feature\\(Auth|Authentication)\\') { return 'Authentication' }
    if ($ClassPath -match '^Feature\\Authorization\\') { return 'Authorization' }
    if ($ClassPath -match '^Feature\\Middleware\\') { return 'Middleware' }
    if ($ClassPath -match '^Feature\\Penugasan\\') { return 'Penugasan' }
    if ($ClassPath -match '^Feature\\Perjalanan\\') { return 'Perjalanan' }
    if ($ClassPath -match '^Feature\\LaporanKerusakan\\') { return 'Kerusakan' }
    if ($ClassPath -match '^Feature\\Perbaikan\\') { return 'Perbaikan' }
    if ($ClassPath -match '^Feature\\Odometer\\') { return 'Odometer' }
    if ($ClassPath -match '^Feature\\Laporan\\') { return 'Export' }
    if ($ClassPath -match '^Feature\\Settings\\') { return 'Settings' }
    if ($ClassPath -match '^Feature\\DashboardTest') { return 'Dashboard' }
    if ($ClassPath -match '^Unit\\') { return 'Unit' }
    if ($ClassPath -match '^Feature\\') { return 'Feature lainnya' }

    return 'perlu diisi manual'
}

try {
    $projectRoot = Assert-ProjectRoot
    $resultDirectory = Join-Path $projectRoot 'storage\app\testing-results'
    if (-not (Test-Path -LiteralPath $resultDirectory -PathType Container)) {
        throw "Folder hasil test tidak ditemukan: $resultDirectory"
    }

    $latestResult = Get-ChildItem -LiteralPath $resultDirectory -File -Filter 'tests-*.txt' |
        Sort-Object LastWriteTime -Descending |
        Select-Object -First 1
    if (-not $latestResult) {
        throw 'File hasil test dengan pola tests-*.txt tidak ditemukan.'
    }

    $text = Get-Content -LiteralPath $latestResult.FullName -Raw
    $summaryLine = ([regex]::Matches($text, 'Tests:\s*[^\r\n]*(?:passed|failed)[^\r\n]*') | Select-Object -Last 1).Value
    $passed = Get-ParsedValue -Text $summaryLine -Pattern '(\d+)\s+passed'
    $failed = if ($summaryLine -match '(\d+)\s+failed') { $Matches[1] } elseif ($summaryLine) { '0' } else { 'perlu diisi manual' }
    $skipped = if ($summaryLine -match '(\d+)\s+skipped') { $Matches[1] } elseif ($summaryLine) { '0' } else { 'perlu diisi manual' }
    $assertions = Get-ParsedValue -Text $summaryLine -Pattern '(\d+)\s+assertions?'
    $duration = Get-ParsedValue -Text $text -Pattern 'Duration:\s*([0-9.]+s)'

    $modules = @{}
    $currentModule = $null
    foreach ($line in ($text -split "`r?`n")) {
        if ($line -match '^\s*(PASS|FAIL|WARN)\s+Tests\\(.+?)\s*$') {
            $classStatus = $Matches[1]
            $currentModule = Get-ModuleName -ClassPath $Matches[2].Trim()
            if (-not $modules.ContainsKey($currentModule)) {
                $modules[$currentModule] = [ordered]@{ Tests = 0; HasFail = $false; HasWarn = $false }
            }
            if ($classStatus -eq 'FAIL') { $modules[$currentModule].HasFail = $true }
            if ($classStatus -eq 'WARN') { $modules[$currentModule].HasWarn = $true }
            continue
        }

        if (-not $currentModule) { continue }
        if ($line -notmatch '^\s*(Tests:|Duration:|Time:)' -and $line -match '^\s+.+\s+[0-9.]+s\s*$') {
            $modules[$currentModule].Tests++
        }
    }

    $markdown = New-Object System.Collections.Generic.List[string]
    $markdown.Add('# Ringkasan Pengujian')
    $markdown.Add('')
    $markdown.Add("- Sumber: ``$($latestResult.Name)``")
    $markdown.Add("- Dibuat: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')")
    $markdown.Add("- Passed: $passed")
    $markdown.Add("- Failed: $failed")
    $markdown.Add("- Skipped: $skipped")
    $markdown.Add("- Assertions: $assertions")
    $markdown.Add("- Durasi: $duration")
    $markdown.Add('')
    $markdown.Add('| Modul | Jumlah test | Jumlah assertion | Status |')
    $markdown.Add('|---|---:|---:|---|')

    if ($modules.Count -eq 0) {
        $markdown.Add('| perlu diisi manual | perlu diisi manual | perlu diisi manual | perlu diisi manual |')
    } else {
        foreach ($moduleName in ($modules.Keys | Sort-Object)) {
            $module = $modules[$moduleName]
            $testCount = $module.Tests
            $status = if ($module.HasFail) {
                'Gagal'
            } elseif ($module.HasWarn) {
                'Lulus dengan skipped'
            } else {
                'Lulus'
            }

            $markdown.Add("| $moduleName | $testCount | perlu diisi manual | $status |")
        }
    }

    $markdown.Add('')
    $markdown.Add('Jumlah assertion per modul tidak tersedia pada output TestDox dan ditandai untuk diisi manual.')

    $reportPath = Join-Path $resultDirectory 'ringkasan-pengujian.md'
    [System.IO.File]::WriteAllLines($reportPath, $markdown.ToArray(), (New-Object System.Text.UTF8Encoding($false)))

    $markdown | ForEach-Object { Write-Host $_ }
    Write-Host "`nRingkasan tersimpan: $reportPath" -ForegroundColor Green
    exit 0
} catch {
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
