<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterKend;
use Illuminate\Support\Facades\Hash;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kends = [
            [
                'no_polisi' => 'L1234AB',
                'merk' => 'Toyota',
                'tipe' => 'Innova Zenix',
                'tahun' => 2023,
                'kategori_kendaraan' => 'R4',
                'jenis_kendaraan' => 'RAN UM',
                'keterangan_penggunaan' => 'RANJAB',
                'status' => 'Tersedia',
                'username' => 'L1234AB',
                'password' => Hash::make('password'),
            ],
            [
                'no_polisi' => 'L2345BC',
                'merk' => 'Mitsubishi',
                'tipe' => 'Pajero Sport',
                'tahun' => 2022,
                'kategori_kendaraan' => 'R4',
                'jenis_kendaraan' => 'RAN DINAS',
                'keterangan_penggunaan' => 'OPERASIONAL',
                'status' => 'Dipakai',
                'username' => 'L2345BC',
                'password' => Hash::make('password'),
            ],
            [
                'no_polisi' => 'L3456CD',
                'merk' => 'Honda',
                'tipe' => 'CR-V Gen 6',
                'tahun' => 2024,
                'kategori_kendaraan' => 'R4',
                'jenis_kendaraan' => 'RAN UM',
                'keterangan_penggunaan' => 'RANJAB',
                'status' => 'Perbaikan',
                'username' => 'L3456CD',
                'password' => Hash::make('password'),
            ],
            [
                'no_polisi' => 'L4567DE',
                'merk' => 'Toyota',
                'tipe' => 'Fortuner 2.8',
                'tahun' => 2023,
                'kategori_kendaraan' => 'R4',
                'jenis_kendaraan' => 'RAN DINAS',
                'keterangan_penggunaan' => 'OPERASIONAL',
                'status' => 'Dipakai',
                'username' => 'L4567DE',
                'password' => Hash::make('password'),
            ],
            [
                'no_polisi' => 'L5678EF',
                'merk' => 'Yamaha',
                'tipe' => 'MT-09',
                'tahun' => 2021,
                'kategori_kendaraan' => 'R2',
                'jenis_kendaraan' => 'RANSUS',
                'keterangan_penggunaan' => 'PATWAL',
                'status' => 'Tersedia',
                'username' => 'L5678EF',
                'password' => Hash::make('password'),
            ],
        ];

        foreach($kends as $k) {
            MasterKend::create($k);
        }
    }
}
