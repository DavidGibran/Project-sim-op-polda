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
        <td colspan="9"></td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-weight: bold; font-size: 14px;">DATA RANMOR DINAS R2, R4 DAN R6</td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-weight: bold; font-size: 12px;">SATKER BID TIK POLDA JATIM</td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline;">LAPORAN KERUSAKAN KENDARAAN</td>
    </tr>
    <tr>
        <td colspan="9"></td>
    </tr>
    
    <!-- Table Header (Row 9) -->
    <tr>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO LAPORAN</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO.POL</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">MERK</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TIPE</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">KELUHAN</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">DETAIL TEKNIS</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TGL LAPOR</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">STATUS</th>
    </tr>

    <!-- Table Body -->
    @php $no = 1; @endphp
    @foreach($kerusakans as $laporan)
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $no++ }}</td>
            <td style="border: 1px solid #000000;">{{ $laporan->no_laporan ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $laporan->kendaraan->no_polisi ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $laporan->kendaraan->merk ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $laporan->kendaraan->tipe ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $laporan->keluhan ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $laporan->detail_teknis ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $laporan->tanggal_lapor ? \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('Y-m-d') : '-' }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($laporan->status ?? '-') }}</td>
        </tr>
    @endforeach
    
    <!-- Signature space -->
    <tr><td colspan="9"></td></tr>
    <tr><td colspan="9"></td></tr>
    <tr>
        <td colspan="6"></td>
        <td colspan="3" style="text-align: center;">Mengetahui,</td>
    </tr>
    <tr>
        <td colspan="6"></td>
        <td colspan="3" style="text-align: center; font-weight: bold;">KABID TIK POLDA JATIM</td>
    </tr>
    <tr><td colspan="9"></td></tr>
    <tr><td colspan="9"></td></tr>
    <tr>
        <td colspan="6"></td>
        <td colspan="3" style="text-align: center;">___________________________</td>
    </tr>
</table>
