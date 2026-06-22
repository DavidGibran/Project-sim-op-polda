<?php

use App\Models\MasterKend;
use App\Models\Penugasan;

test('vehicle can start an accepted assignment', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Dipakai']);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diterima',
        'waktu_mulai' => null,
    ]);

    $this->withKendaraanSession($kendaraan)
        ->from(route('kendaraan.perjalanan-aktif'))
        ->post(route('kendaraan.penugasan.mulai', $penugasan))
        ->assertRedirect(route('kendaraan.perjalanan-aktif'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'berjalan',
    ]);
    expect($penugasan->refresh()->waktu_mulai)->not->toBeNull();
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Dipakai',
    ]);
});

test('vehicle cannot start an unpublished assignment', function () {
    $kendaraan = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diterbitkan',
    ]);

    $this->withKendaraanSession($kendaraan)
        ->from(route('kendaraan.perjalanan-aktif'))
        ->post(route('kendaraan.penugasan.mulai', $penugasan))
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'berjalan',
    ]);
});
