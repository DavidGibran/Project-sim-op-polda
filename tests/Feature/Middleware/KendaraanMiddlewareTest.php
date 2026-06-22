<?php

use App\Models\MasterKend;

test('request without vehicle session is redirected', function () {
    $this->get('/kendaraan/dashboard')
        ->assertRedirect(route('login'));
});

test('request with unknown vehicle session is redirected', function () {
    $this->withSession(['kendaraan_id' => 999999])
        ->get('/kendaraan/dashboard')
        ->assertRedirect(route('login'))
        ->assertSessionHas('error');
});

test('request with valid vehicle session can access vehicle area', function () {
    $kendaraan = MasterKend::factory()->create();

    $this->withKendaraanSession($kendaraan)
        ->get('/kendaraan/dashboard')
        ->assertOk();
});
