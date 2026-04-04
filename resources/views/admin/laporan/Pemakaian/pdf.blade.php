<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemakaian</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        table th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Laporan Pemakaian Kendaraan</h2>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Pengemudi</th>
            <th>Nopol</th>
            <th>Jenis</th>
            <th>Tipe</th>
            <th>Tujuan</th>
            <th>KM Awal</th>
            <th>KM Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>#{{ $log->id }}</td>
                <td>{{ $log->pengemudi }}</td>
                <td>{{ $log->kendaraan->no_polisi ?? '-' }}</td>
                <td>{{ $log->kendaraan->jenis_kendaraan ?? '-' }}</td>
                <td>{{ $log->kendaraan->tipe ?? '-' }}</td>
                <td>{{ $log->tujuan }}</td>
                <td>{{ $log->km_awal }}</td>
                <td>{{ $log->km_akhir }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>