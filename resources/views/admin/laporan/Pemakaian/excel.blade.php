<table>
    <tr>
        <td colspan="3" style="font-weight: bold;">KEPOLISIAN NEGARA REPUBLIK INDONESIA</td>
    </tr>
    <tr>
        <td colspan="3" style="font-weight: bold;">DAERAH JAWA TIMUR</td>
    </tr>
    <tr>
        <td colspan="3" style="font-weight: bold;">BIDANG TEKNOLOGI INFORMASI DAN KOMUNIKASI</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 14px;">DATA RANMOR DINAS R2, R4 DAN R6</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 12px;">SATKER BID TIK POLDA JATIM</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline;">LAPORAN PEMAKAIAN KENDARAAN</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    
    <!-- Table Header (Row 9) -->
    <tr>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">ID TUGAS</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">PENGEMUDI</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO.POL</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TIPE KENDARAAN</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TUJUAN</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">KM AWAL</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">KM AKHIR</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">CATATAN</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">STATUS</th>
    </tr>

    <!-- Table Body -->
    @php $no = 1; @endphp
    @foreach($logs as $log)
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $no++ }}</td>
            <td style="text-align: center; border: 1px solid #000000;">#{{ $log->id }}</td>
            <td style="border: 1px solid #000000;">{{ $log->pengemudi }}</td>
            <td style="border: 1px solid #000000;">{{ $log->kendaraan->no_polisi ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ trim(($log->kendaraan->jenis_kendaraan ?? '') . ' ' . ($log->kendaraan->tipe ?? '')) }}</td>
            <td style="border: 1px solid #000000;">{{ $log->tujuan }}</td>
            <td style="text-align: right; border: 1px solid #000000;">{{ $log->km_awal ?? 0 }}</td>
            <td style="text-align: right; border: 1px solid #000000;">{{ $log->km_akhir ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $log->catatan ?: '-' }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($log->status ?? '-') }}</td>
        </tr>
    @endforeach
    
    <!-- Signature space -->
    <tr><td colspan="10"></td></tr>
    <tr><td colspan="10"></td></tr>
    <tr>
        <td colspan="7"></td>
        <td colspan="3" style="text-align: center;">Mengetahui,</td>
    </tr>
    <tr>
        <td colspan="7"></td>
        <td colspan="3" style="text-align: center; font-weight: bold;">KABID TIK POLDA JATIM</td>
    </tr>
    <tr><td colspan="10"></td></tr>
    <tr><td colspan="10"></td></tr>
    <tr>
        <td colspan="7"></td>
        <td colspan="3" style="text-align: center;">___________________________</td>
    </tr>
</table>