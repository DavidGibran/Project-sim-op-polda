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
        <td colspan="9" style="text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline;">LAPORAN PERBAIKAN KENDARAAN</td>
    </tr>
    <tr>
        <td colspan="9"></td>
    </tr>
    
    <!-- Table Header (Row 9) -->
    <tr>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">NO.POL</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">MERK</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TIPE</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">DETAIL PERBAIKAN</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TGL LAPOR</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">TGL SELESAI</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">BIAYA (Rp)</th>
        <th style="text-align: center; font-weight: bold; background-color: #FFD966; border: 1px solid #000000;">STATUS</th>
    </tr>

    <!-- Table Body -->
    @php $no = 1; @endphp
    @foreach($perbaikans as $perbaikan)
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $no++ }}</td>
            <td style="border: 1px solid #000000;">{{ $perbaikan->kendaraan->no_polisi ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $perbaikan->kendaraan->merk ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $perbaikan->kendaraan->tipe ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $perbaikan->catatan ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $perbaikan->tanggal_lapor ? \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->format('Y-m-d') : '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $perbaikan->tgl_selesai ? \Carbon\Carbon::parse($perbaikan->tgl_selesai)->format('Y-m-d') : '-' }}</td>
            <td style="text-align: right; border: 1px solid #000000;">{{ $perbaikan->biaya ?? 0 }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($perbaikan->status ?? '-') }}</td>
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
