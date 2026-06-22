<?php

use App\Models\MasterKend;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
});

test('admin can access stored odometer photo', function () {
    $penugasan = Penugasan::factory()->create([
        'foto_odometer' => 'odometer/admin-photo.jpg',
    ]);
    Storage::disk('local')->put($penugasan->foto_odometer, 'image-content');

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('penugasan.odometer-foto', $penugasan))
        ->assertOk();

    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'foto_odometer' => 'odometer/admin-photo.jpg',
    ]);
});

test('assigned vehicle can access its odometer photo', function () {
    $kendaraan = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'foto_odometer' => 'odometer/vehicle-photo.jpg',
    ]);
    Storage::disk('local')->put($penugasan->foto_odometer, 'image-content');

    $this->withKendaraanSession($kendaraan)
        ->get(route('penugasan.odometer-foto', $penugasan))
        ->assertOk();
});

test('another vehicle cannot access odometer photo', function () {
    $kendaraanLogin = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'foto_odometer' => 'odometer/private-photo.jpg',
    ]);
    Storage::disk('local')->put($penugasan->foto_odometer, 'image-content');

    $this->withKendaraanSession($kendaraanLogin)
        ->get(route('penugasan.odometer-foto', $penugasan))
        ->assertForbidden();

    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'foto_odometer' => null,
    ]);
});

test('authorized request receives not found for missing photo', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $penugasan = Penugasan::factory()->create([
        'foto_odometer' => 'odometer/missing.jpg',
    ]);

    $this->actingAs($admin)
        ->get(route('penugasan.odometer-foto', $penugasan))
        ->assertNotFound();
});
