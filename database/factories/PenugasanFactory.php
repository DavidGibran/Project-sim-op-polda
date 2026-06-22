<?php

namespace Database\Factories;

use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penugasan>
 */
class PenugasanFactory extends Factory
{
    protected $model = Penugasan::class;

    public function definition(): array
    {
        return [
            'id_kend' => MasterKend::factory(),
            'pengemudi' => fake()->name(),
            'tgl_tugas' => today(),
            'tgl_selesai' => null,
            'tujuan' => fake()->city(),
            'km_awal' => fake()->numberBetween(0, 100000),
            'km_akhir' => null,
            'catatan' => null,
            'status' => 'diterbitkan',
            'waktu_mulai' => null,
            'waktu_selesai' => null,
            'foto_odometer' => null,
        ];
    }
}
