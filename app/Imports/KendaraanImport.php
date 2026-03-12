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

        // Flexible column mapping with fallbacks
        // Laravel Excel heading row usually converts "NO.POL" to "no_pol" or "no_polisi"
        $noPolisiRaw = $row['no_pol'] ?? $row['nopol'] ?? $row['no_polisi'] ?? $row['nomor_polisi'] ?? null;
        
        // Skip row if license plate is missing
        if (empty($noPolisiRaw)) {
            Log::warning('Skipping row due to missing NO.POL');
            return null;
        }

        // Sanitize license plate: uppercase and remove spaces
        $noPolisi = strtoupper(str_replace(' ', '', (string)$noPolisiRaw));

        // Other fields with fallbacks
        $merk = $row['merk'] ?? $row['merek'] ?? '';
        $tipe = $row['nama_pada_simak'] ?? $row['tipe'] ?? '';
        $tahun = $row['tahun'] ?? date('Y');
        $jenis = $row['jenis'] ?? $row['jenis_kendaraan'] ?? '';
        $keterangan = $row['ket'] ?? $row['keterangan'] ?? $row['keterangan_penggunaan'] ?? '';

        $kategori = 'R4'; // Default
        if (stripos((string)$jenis, 'R2') !== false || stripos((string)$jenis, 'Motor') !== false) {
            $kategori = 'R2';
        }

        // Check if vehicle exists
        $kendaraan = MasterKend::where('no_polisi', $noPolisi)->first();

        if ($kendaraan) {
            $kendaraan->update([
                'merk'                  => $merk,
                'tipe'                  => $tipe,
                'tahun'                 => $tahun,
                'jenis_kendaraan'       => $jenis,
                'keterangan_penggunaan' => $keterangan,
                'kategori_kendaraan'    => $kategori,
                'status'                => 'Tersedia',
                'username'              => $noPolisi,
            ]);
            return null; // Return null because we updated it manually
        }

        return new MasterKend([
            'no_polisi'             => $noPolisi,
            'merk'                  => $merk,
            'tipe'                  => $tipe,
            'tahun'                 => $tahun,
            'jenis_kendaraan'       => $jenis,
            'keterangan_penggunaan' => $keterangan,
            'kategori_kendaraan'    => $kategori,
            'status'                => 'Tersedia',
            'username'              => $noPolisi,
            'password'              => 'kendaraan123', // Model cast will handle hashing
        ]);
    }
}
