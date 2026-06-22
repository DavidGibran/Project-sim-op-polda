<?php

namespace Database\Factories;

use App\Models\LaporanKerusakan;
use App\Models\MasterKend;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaporanKerusakan>
 */
class LaporanKerusakanFactory extends Factory
{
    protected $model = LaporanKerusakan::class;

    public function definition(): array
    {
        return [
            'no_laporan' => 'REP-'.now()->format('Ymd').'-'.fake()->unique()->numerify('####'),
            'id_kend' => MasterKend::factory(),
            'id_penugasan' => null,
            'sumber' => 'kendaraan',
            'mode' => 'simple',
            'keluhan' => fake()->sentence(),
            'detail_teknis' => null,
            'nomor_hp' => fake()->numerify('08##########'),
            'status' => 'diterbitkan',
            'tanggal_lapor' => now(),
        ];
    }
}
