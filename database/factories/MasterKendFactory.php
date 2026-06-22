<?php

namespace Database\Factories;

use App\Models\MasterKend;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<MasterKend>
 */
class MasterKendFactory extends Factory
{
    protected $model = MasterKend::class;

    public function definition(): array
    {
        $noPolisi = fake()->unique()->bothify('? #### ??');

        return [
            'no_polisi' => strtoupper($noPolisi),
            'nama_pemegang' => fake()->name(),
            'merk' => fake()->randomElement(['Toyota', 'Honda', 'Mitsubishi']),
            'tipe' => fake()->word(),
            'tahun' => fake()->numberBetween(2015, 2026),
            'bbm' => fake()->randomElement(['PERTAMAX', 'PERTAMINA DEX']),
            'nama_pada_simak' => fake()->name(),
            'kategori_kendaraan' => fake()->randomElement(['R2', 'R4']),
            'jenis_kendaraan' => fake()->randomElement(['RANUM', 'RANSUS']),
            'keterangan_penggunaan' => 'OPERASIONAL',
            'km_terakhir' => fake()->numberBetween(0, 100000),
            'status' => 'Tersedia',
            'username' => fake()->unique()->userName(),
            'password' => Hash::make('password'),
        ];
    }
}
