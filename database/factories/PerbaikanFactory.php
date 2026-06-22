<?php

namespace Database\Factories;

use App\Models\LaporanKerusakan;
use App\Models\Perbaikan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Perbaikan>
 */
class PerbaikanFactory extends Factory
{
    protected $model = Perbaikan::class;

    public function definition(): array
    {
        return [
            'id_laporan' => LaporanKerusakan::factory(),
            'id_kend' => fn (array $attributes) => LaporanKerusakan::findOrFail($attributes['id_laporan'])->id_kend,
            'tanggal_lapor' => today(),
            'keluhan' => fake()->sentence(),
            'status' => 'diproses',
            'teknisi' => fake()->name(),
            'tgl_mulai' => now(),
            'tgl_selesai' => null,
            'biaya' => null,
            'catatan' => null,
        ];
    }
}
