<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan Kendaraan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #1a202c;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-table td {
            border: none;
            padding: 0;
        }
        .kop-text-1 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a202c;
            margin: 0;
        }
        .kop-text-2 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a202c;
            margin: 2px 0 0 0;
        }
        .kop-text-3 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a202c;
            margin: 2px 0 0 0;
        }
        .title-section {
            text-align: center;
            margin-bottom: 15px;
        }
        .title-section h2 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a202c;
        }
        .title-section h3 {
            margin: 2px 0 0 0;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4a5568;
        }
        .title-section h1 {
            margin: 8px 0 0 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2c3e50;
            text-decoration: underline;
        }
        .meta {
            margin-bottom: 15px;
        }
        .meta table {
            width: 100%;
            border: none;
        }
        .meta td {
            padding: 2px 0;
            border: none;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #94a3b8;
            padding: 6px 5px;
        }
        table.data th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
        }
        table.data tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
            background: #e2e8f0;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 220px;
            text-align: center;
        }
        .signature p {
            margin: 0;
            line-height: 1.4;
        }
        .signature .space {
            height: 55px;
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td width="10%" style="vertical-align: middle; text-align: left; padding-bottom: 8px;">
                <img src="{{ public_path('images/logo/logo-tik-polri.png') }}" style="height: 65px; width: auto;">
            </td>
            <td width="90%" style="vertical-align: middle; text-align: left; padding-left: 10px; padding-bottom: 8px;">
                <h3 class="kop-text-1">KEPOLISIAN NEGARA REPUBLIK INDONESIA</h3>
                <h3 class="kop-text-2">DAERAH JAWA TIMUR</h3>
                <h3 class="kop-text-3">BIDANG TEKNOLOGI INFORMASI DAN KOMUNIKASI</h3>
            </td>
        </tr>
    </table>

    <!-- JUDUL LAPORAN -->
    <div class="title-section">
        <h2>DATA RANMOR DINAS R2, R4 DAN R6</h2>
        <h3>SATKER BID TIK POLDA JATIM</h3>
        <h1>LAPORAN PERBAIKAN KENDARAAN</h1>
    </div>

    <!-- META DATA -->
    <div class="meta">
        <table style="width: 100%;">
            <tr>
                <td width="12%"><strong>Dicetak Pada</strong></td>
                <td width="38%">: {{ $printedAt->format('d-m-Y H:i') }} WIB</td>
                <td width="12%"><strong>Filter Periode</strong></td>
                <td width="38%">: {{ $tanggalDari ? \Carbon\Carbon::parse($tanggalDari)->format('d-m-Y') : '-' }} s/d {{ $tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Kata Kunci</strong></td>
                <td>: {{ $search ?: 'Semua Data' }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <!-- DATA TABEL -->
    <table class="data">
        <thead>
            <tr>
                <th width="4%" class="text-center">NO</th>
                <th width="10%">NO POLISI</th>
                <th width="13%">MERK</th>
                <th width="13%">TIPE</th>
                <th width="20%">DETAIL PERBAIKAN</th>
                <th width="10%" class="text-center">TGL LAPOR</th>
                <th width="10%" class="text-center">TGL SELESAI</th>
                <th width="10%" class="text-right">BIAYA (Rp)</th>
                <th width="10%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perbaikans as $index => $perbaikan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $perbaikan->kendaraan->no_polisi ?? '-' }}</strong></td>
                    <td>{{ $perbaikan->kendaraan->merk ?? '-' }}</td>
                    <td>{{ $perbaikan->kendaraan->tipe ?? '-' }}</td>
                    <td>{{ $perbaikan->catatan ?? '-' }}</td>
                    <td class="text-center">{{ $perbaikan->tanggal_lapor ? \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">{{ $perbaikan->tgl_selesai ? \Carbon\Carbon::parse($perbaikan->tgl_selesai)->format('d-m-Y') : '-' }}</td>
                    <td class="text-right">{{ number_format($perbaikan->biaya ?? 0, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($perbaikan->status ?? '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; font-style: italic;">Tidak ada data laporan perbaikan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="footer">
        <div class="signature">
            <p>Mengetahui,</p>
            <p><strong>KABID TIK POLDA JATIM</strong></p>
            <div class="space"></div>
            <p>___________________________</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>