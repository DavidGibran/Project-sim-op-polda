<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@polda.local',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Admin Tambahan',
            'email' => 'admin2@polda.local',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);
    }
}
