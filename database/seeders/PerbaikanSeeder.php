<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perbaikan;
use Carbon\Carbon;

class PerbaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perbaikan::create([
            'id_kend' => 4, // L4567DE
            'tanggal_lapor' => Carbon::now()->subDays(1),
            'keluhan' => 'Rem tidak pakem',
            'status' => 'diproses',
            'teknisi' => 'Pak Joko',
            'tgl_mulai' => Carbon::now()
        ]);

        Perbaikan::create([
            'id_kend' => 5, // L5678EF
            'tanggal_lapor' => Carbon::now()->subDays(5),
            'keluhan' => 'Ganti oli dan filter',
            'status' => 'selesai',
            'teknisi' => 'Bengkel Resmi',
            'tgl_mulai' => Carbon::now()->subDays(4),
            'tgl_selesai' => Carbon::now()->subDays(3),
            'biaya' => 1500000,
            'catatan' => 'Oli gardan juga diganti'
        ]);
    }
}
