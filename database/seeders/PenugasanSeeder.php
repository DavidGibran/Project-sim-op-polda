<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penugasan;
use Carbon\Carbon;

class PenugasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data 1
        Penugasan::create([
            'id_kend'   => 1,
            'pengemudi' => 'Adam',
            'tgl_tugas' => Carbon::today(),
            'tujuan'    => 'Polda Jatim',
            'status'    => 'diterbitkan'
        ]);

        // Data 2
        Penugasan::create([
            'id_kend'   => 2,
            'pengemudi' => 'Budi',
            'tgl_tugas' => Carbon::yesterday(),
            'tujuan'    => 'Polrestabes Surabaya',
            'status'    => 'berjalan',
            'km_awal'   => 54000, // Ganti dari odo_awal
            // prj_mulai dihapus karena sudah tidak ada di kolom tabel
        ]);

        // Data 3
        Penugasan::create([
            'id_kend'     => 3,
            'pengemudi'   => 'Rizal',
            'tgl_tugas'   => Carbon::now()->subDays(2),
            'tgl_selesai' => Carbon::now()->subDays(2), // Tambahan kolom baru dari migrasi
            'tujuan'      => 'Patroli Kota',
            'status'      => 'selesai',
            'km_awal'     => 31950, // Ganti dari odo_awal
            'km_akhir'    => 32000, // Ganti dari odo_akhir
            // prj_mulai dan prj_selesai dihapus
        ]);
    }
}
