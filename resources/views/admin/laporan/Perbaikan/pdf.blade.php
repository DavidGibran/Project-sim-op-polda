<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan</title>
    <style>
        /* Styling dasar untuk PDF */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin-bottom: 8px;
        }

        .meta {
            margin-bottom: 16px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #222;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #f3f3f3;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    {{-- Judul laporan --}}
    <h2>Laporan Perbaikan Kendaraan</h2>

    {{-- Informasi filter --}}
    <div class="meta">
        <div><strong>Dicetak:</strong> {{ $printedAt->format('d-m-Y H:i') }}</div>
        <div><strong>Tanggal Filter:</strong> {{ $tanggalDari ?: '-' }} s/d {{ $tanggalSampai ?: '-' }}</div>
        <div><strong>Pencarian:</strong> {{ $search ?: '-' }}</div>
    </div>

    {{-- Tabel laporan --}}
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>No Polisi</th>
                <th>Merk</th>
                <th>Tipe</th>
                <th>Keluhan</th>
                <th>Tanggal Lapor</th>
                <th>Tanggal Selesai</th>
                <th>Biaya</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perbaikans as $index => $perbaikan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $perbaikan->kendaraan->no_polisi ?? '-' }}</td>
                    <td>{{ $perbaikan->kendaraan->merk ?? '-' }}</td>
                    <td>{{ $perbaikan->kendaraan->tipe ?? '-' }}</td>
                    <td>{{ $perbaikan->keluhan ?? '-' }}</td>
                    <td>{{ $perbaikan->tanggal_lapor ? \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $perbaikan->tgl_selesai ? \Carbon\Carbon::parse($perbaikan->tgl_selesai)->format('d-m-Y') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format($perbaikan->biaya ?? 0, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($perbaikan->status ?? '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>