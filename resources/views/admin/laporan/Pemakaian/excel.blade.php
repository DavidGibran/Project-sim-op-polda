<table>
    <thead>
        <tr>
            <th>Kode Tugas</th>
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
                <td>{{ $log->kode_tugas }}</td>
                <td>{{ $log->nama_pengemudi }}</td>
                <td>{{ $log->nopol }}</td>
                <td>{{ $log->jenis_kendaraan }}</td>
                <td>{{ $log->tipe_kendaraan }}</td>
                <td>{{ $log->tujuan }}</td>
                <td>{{ $log->km_awal }}</td>
                <td>{{ $log->km_akhir }}</td>
            </tr>
        @endforeach
    </tbody>
</table>