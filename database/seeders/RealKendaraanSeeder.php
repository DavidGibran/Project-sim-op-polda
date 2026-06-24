<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealKendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['TOYOTA INNOVA ZENIX', 'JEEP', '21000-X', '2024', 'PERTAMAX', 'BRIPTU SURYA', 'RAN UM', 'RANJAB'],
            ['HYUNDAI TUCSON 2.0 AT', 'JEEP', '214-X', '2013', 'PERTAMAX', 'AKBP ANDJAR', 'RAN UM', 'RANJAB'],
            ['TOYOTA KIJANG LSX', 'MINIBUS', '212-X', '2004', 'PERTAMAX', 'IPDA SIMON', 'RAN UM', 'OPRASIONAL'],
            ['HONDA VERZA', 'SEPEDA MOTOR', '21210-X', '2013', 'PERTAMAX', 'AIPTU WAKIT', 'RAN UM', 'OPRASIONAL'],
            ['HONDA VERZA', 'SEPEDA MOTOR', '21211-X', '2013', 'PERTAMAX', 'AIPDA DANIEL', 'RAN UM', 'OPRASIONAL'],
            ['HONDA SUPRA X', 'SEPEDA MOTOR', '2114-X', '2016', 'PERTAMAX', 'BRIPTU FAISAL', 'RAN UM', 'OPRASIONAL'],
            ['HONDA VERZA', 'SEPEDA MOTOR', '2115-X', '2016', 'PERTAMAX', 'BRIPDA RISKI', 'RAN UM', 'OPRASIONAL'],
            ['KAWASAKI KLX', 'SEPEDA MOTOR', '2116-X', '2017', 'PERTAMAX', 'BRIPDA FITRA DEA A.', 'RAN UM', 'OPRASIONAL'],
            ['KAWASAKI KLX', 'SEPEDA MOTOR', '2117-X', '2017', 'PERTAMAX', 'BRIGADIR EDY SANTOSO', 'RAN UM', 'OPRASIONAL'],
            ['ALVA', 'SEPEDA MOTOR', '2118-X', '2024', 'LISTRIK', 'SPRI KABID TIK', 'RAN UM', 'OPRASIONAL'],
            ['ALVA', 'SEPEDA MOTOR', '2119-X', '2024', 'LISTRIK', 'KASUBBAGRENMIN', 'RAN UM', 'OPRASIONAL'],
            ['MITSUBISHI PAJERO SPORT EXCEED 4X2 AT', 'JEEP', '21-X', '2019', 'PERTAMINA DEX', 'BRIPDA GABRIEL', 'RAN UM', 'RANJAB'],
            ['TOYOTA HILUX', 'KENDARAAN DOUBLE CABIN 4X4', '2122-X', '2022', 'PERTAMINA DEX', 'BRIPDA FERY', 'RAN UM', 'RANJAB'],
            ['FORD RANGER DOUBLE CABIN 4X4 BASE MT 2,5L VEHICLE FLY AWAY', 'MOBILE MONITORING FREKWENSI', '2128-X', '2010', 'PERTAMINA DEX', 'KOMPOL ZAINUDDIN', 'RANSUS', 'OPRASIONAL'],
            ['FORD RANGER DOUBLE CABIN 4X4 BASE MT 2,5L VEHICLE TSV', 'MOBILE MONITORING FREKWENSI', '2129-X', '2010', 'PERTAMINA DEX', 'PENATA TK.I EDI SAPTANA', 'RANSUS', 'OPRASIONAL'],
            ['FORD SUV 4X4 EVEREST 2,5L LXT VEHICLE MTC', 'MOBILE MONITORING FREKWENSI', '2127-X', '2010', 'PERTAMINA DEX', 'KOMPOL SADIR', 'RANSUS', 'OPRASIONAL'],
            ['ISUZU D-MAX RODEO LS DOUBLE CABIN', 'MOBILE MONITORING FREKWENSI', '2123-X', '2004', 'PERTAMINA DEX', 'BRIPDA RAFLI', 'RANSUS', 'OPRASIONAL'],
            ['MITSUBISHI KOMOB (MOBIL KOMUNIKASI SATELIT)', 'MOBIL KOMUNIKASI SATELIT', '2124-X', '2009', 'PERTAMINA DEX', 'BRIPTU FAJAR', 'RANSUS', 'OPRASIONAL'],
            ['MOBIL TACTICAL COMUNICATION (HINO BUS SOUND SYSTEM)', 'KENDARAAN KHUSUS LAINNYA', '2125-X', '2018', 'PERTAMINA DEX', 'BRIPTU JOGI', 'RANSUS', 'OPRASIONAL'],
            ['ISUZU ELF (TRUK VIDCON)', 'TACTICAL MOBILE VIDEO CONFERENCE', '2121-X', '2022', 'PERTAMINA DEX', 'PENATA TK.I ARYANTO', 'RANSUS', 'OPRASIONAL'],
            ['HINO KOMOB (MOBIL KOMLEK POLRI)', 'MOBIL KOMLEK POLRI', '2130-X', '2025', 'PERTAMINA DEX', 'BRIPDA RENDYKA', 'RANSUS', 'OPRASIONAL'],
            ['MOBIL DF ZENIX', '-', '-', '2025', 'PERTAMAX', 'PINJAM PAKAI', 'RANSUS', 'PINJAM PAKAI (TEKKOM)'],
            ['TOYOTA SOLUNA', 'SEDAN', '213-X', '2002', 'PERTAMAX', '-', 'RAN UM', 'RUSAK BERAT PROSES HAPUS'],
        ];

        foreach ($data as $row) {
            $fullMerk = $row[0];
            $namaSimak = $row[1];
            $noPolisiRaw = $row[2];
            $tahun = $row[3];
            $bbm = $row[4];
            $pemegang = $row[5];
            $jenisRaw = $row[6];
            $ket = $row[7];

            // Split Merk & Tipe
            $parts = explode(' ', $fullMerk, 2);
            $merk = $parts[0];
            $tipe = isset($parts[1]) ? $parts[1] : '-';

            // Normalisasi Jenis
            $jenis = str_replace(' ', '', strtoupper($jenisRaw));
            if (!in_array($jenis, ['RANUM', 'RANSUS', 'LAINNYA'])) {
                $jenis = 'LAINNYA';
            }

            // Kategori Kendaraan Heuristic
            $kategori = 'Lainnya';
            $simakUpper = strtoupper($namaSimak);
            if (str_contains($simakUpper, 'SEPEDA MOTOR')) {
                $kategori = 'R2';
            } elseif (str_contains($simakUpper, 'JEEP') || str_contains($simakUpper, 'MINIBUS') || str_contains($simakUpper, 'DOUBLE CABIN') || str_contains($simakUpper, 'SEDAN')) {
                $kategori = 'R4';
            } elseif (str_contains($simakUpper, 'ELF') || str_contains($simakUpper, 'TRUK') || str_contains($simakUpper, 'BUS') || str_contains($simakUpper, 'KOMOB') || str_contains($simakUpper, 'TACTICAL')) {
                $kategori = 'R6';
            }

            // No Polisi & Username
            $noPolisi = ($noPolisiRaw === '-') ? 'TANPA-NOPOL-' . uniqid() : $noPolisiRaw;
            $username = strtolower(str_replace(' ', '', $noPolisi));

            \App\Models\MasterKend::create([
                'no_polisi'             => ($noPolisiRaw === '-') ? '-' : $noPolisiRaw,
                'nama_pemegang'         => ($pemegang === '-' || empty($pemegang)) ? '-' : $pemegang,
                'username'              => $username,
                'password'              => bcrypt('password123'),
                'merk'                  => $merk,
                'tipe'                  => $tipe,
                'kategori_kendaraan'    => $kategori,
                'jenis_kendaraan'       => $jenis,
                'km_terakhir'           => 0,
                'keterangan_penggunaan' => ($ket === '-' || empty($ket)) ? '-' : $ket,
                'tahun'                 => $tahun,
                'bbm'                   => $bbm,
                'nama_pada_simak'       => $namaSimak,
                'status'                => 'Tersedia',
            ]);
        }
    }
}
