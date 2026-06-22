<?php

use App\Models\MasterKend;
use App\Models\User;

test('login page can be opened', function () {
    $this->get('/login')->assertOk();
});

test('valid admin can log in', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->loginAsAdmin($admin)
        ->assertRedirect(route('admin.dashboard', absolute: false));

    $this->assertAuthenticatedAs($admin);
});

test('admin with wrong password is rejected', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->loginAsAdmin($admin, 'wrong-password')
        ->assertSessionHasErrors('username');

    $this->assertGuest();
});

test('valid vehicle can log in', function () {
    $kendaraan = MasterKend::factory()->create();

    $this->loginAsKendaraan($kendaraan)
        ->assertRedirect(route('kendaraan.dashboard', absolute: false))
        ->assertSessionHas('kendaraan_id', $kendaraan->id_kend);

    $this->assertGuest();
});

test('vehicle with wrong password is rejected', function () {
    $kendaraan = MasterKend::factory()->create();

    $this->loginAsKendaraan($kendaraan, 'wrong-password')
        ->assertSessionHasErrors('username')
        ->assertSessionMissing('kendaraan_id');
});

test('admin page cannot be accessed without login', function () {
    $this->get('/admin/dashboard')->assertRedirect(route('login'));
});

test('vehicle page cannot be accessed without vehicle session', function () {
    $this->get('/kendaraan/dashboard')->assertRedirect(route('login'));
});
