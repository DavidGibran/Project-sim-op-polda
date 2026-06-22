<?php

namespace Tests;

use App\Models\MasterKend;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    public function loginAsAdmin(?User $user = null, string $password = 'password'): TestResponse
    {
        $user ??= User::factory()->create(['role' => 'admin']);

        return $this->post('/auth/login', [
            'username' => $user->email,
            'password' => $password,
        ]);
    }

    public function loginAsKendaraan(?MasterKend $kendaraan = null, string $password = 'password'): TestResponse
    {
        $kendaraan ??= MasterKend::factory()->create();

        return $this->post('/auth/login', [
            'username' => $kendaraan->username,
            'password' => $password,
        ]);
    }

    public function withKendaraanSession(MasterKend $kendaraan): static
    {
        return $this->withSession([
            'kendaraan_id' => $kendaraan->id_kend,
            'kendaraan_polisi' => $kendaraan->no_polisi,
        ]);
    }
}
