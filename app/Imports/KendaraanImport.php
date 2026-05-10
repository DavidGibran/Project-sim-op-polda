<?php

namespace App\Imports;

use App\Models\MasterKend;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class KendaraanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Logging for debugging
        Log::info('Importing Row:', $row);

        // Required field: NO_POLISI
        $noPolisiRaw = $row['no_polisi'] ?? $row['no_pol'] ?? $row['nopol'] ?? null;
        
        if (empty($noPolisiRaw)) {
            Log::warning('Skipping row due to missing NO_POLISI');
            return null;
        }

        $noPolisi = strtoupper(str_replace(' ', '', (string)$noPolisiRaw));

        // Other fields mapping
        $merk = $row['merk'] ?? $row['merek'] ?? '-';
        $tipe = $row['tipe'] ?? '-';
        $namaPadaSimak = $row['nama_pada_simak'] ?? $row['nama_simak'] ?? '-';
        $tahun = $row['tahun'] ?? date('Y');
        $bbm = $row['bbm'] ?? $row['bahan_bakar'] ?? '-';
        $kategori = $row['kategori_kendaraan'] ?? $row['kategori'] ?? 'R4';
        $jenis = strtoupper($row['jenis_kendaraan'] ?? $row['jenis'] ?? 'RANUM');
        
        // Validation for jenis_kendaraan
        if (!in_array($jenis, ['RANUM', 'RANSUS', 'LAINNYA'])) {
            $jenis = 'LAINNYA';
        }

        $kmTerakhir = $row['km_terakhir'] ?? $row['km'] ?? 0;
        if (!is_numeric($kmTerakhir)) $kmTerakhir = 0;

        $status = $row['status_kendaraan'] ?? $row['status'] ?? 'Tersedia';
        
        // Optional fields with fallback '-'
        $namaPemegang = $row['nama_pemegang'] ?? '-';
        if (empty($namaPemegang)) $namaPemegang = '-';

        $keterangan = $row['keterangan_penggunaan'] ?? $row['keterangan'] ?? '-';
        if (empty($keterangan)) $keterangan = '-';

        // Check if vehicle exists
        $kendaraan = MasterKend::where('no_polisi', $noPolisi)->first();

        $data = [
            'nama_pemegang'         => $namaPemegang,
            'merk'                  => $merk,
            'tipe'                  => $tipe,
            'nama_pada_simak'       => $namaPadaSimak,
            'tahun'                 => $tahun,
            'bbm'                   => $bbm,
            'kategori_kendaraan'    => $kategori,
            'jenis_kendaraan'       => $jenis,
            'km_terakhir'           => $kmTerakhir,
            'status'                => $status,
            'keterangan_penggunaan' => $keterangan,
            'username'              => strtolower($noPolisi),
        ];

        if ($kendaraan) {
            $kendaraan->update($data);
            return null; 
        }

        // Add password for new data
        $data['no_polisi'] = $noPolisi;
        $data['password'] = 'kendaraan123';

        return new MasterKend($data);
    }
}
