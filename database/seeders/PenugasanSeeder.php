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
        Penugasan::create([
            'id_kend' => 1, // L1234AB
            'nama_sopir' => 'Adam',
            'tgl_tugas' => Carbon::today(),
            'tujuan' => 'Polda Jatim',
            'kegiatan' => 'Rapat Koordinasi',
            'status' => 'diterbitkan'
        ]);

        Penugasan::create([
            'id_kend' => 2, // L2345BC
            'nama_sopir' => 'Budi',
            'tgl_tugas' => Carbon::yesterday(),
            'tujuan' => 'Polrestabes Surabaya',
            'kegiatan' => 'Pengamanan Demo',
            'status' => 'berjalan',
            'odo_awal' => 54000,
            'prj_mulai' => Carbon::yesterday()->setHour(8)
        ]);

        Penugasan::create([
            'id_kend' => 3, // L3456CD
            'nama_sopir' => 'Rizal',
            'tgl_tugas' => Carbon::now()->subDays(2),
            'tujuan' => 'Patroli Kota',
            'kegiatan' => 'Patroli Rutin',
            'status' => 'selesai',
            'odo_awal' => 31950,
            'odo_akhir' => 32000,
            'prj_mulai' => Carbon::now()->subDays(2)->setHour(9),
            'prj_selesai' => Carbon::now()->subDays(2)->setHour(17)
        ]);
    }
}
