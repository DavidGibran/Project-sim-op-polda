<?php

use App\Models\MasterKend;
use App\Models\Penugasan;

test('vehicle can accept its published assignment', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Tersedia']);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diterbitkan',
    ]);

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.penugasan.terima', $penugasan))
        ->assertRedirect(route('kendaraan.perjalanan-aktif'));

    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'diterima',
    ]);
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Dipakai',
    ]);
});

test('vehicle cannot accept an assignment in another state', function () {
    $kendaraan = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'selesai',
        'km_akhir' => 15000,
    ]);

    $this->withKendaraanSession($kendaraan)
        ->from(route('kendaraan.perjalanan-aktif'))
        ->post(route('kendaraan.penugasan.terima', $penugasan))
        ->assertRedirect(route('kendaraan.perjalanan-aktif'))
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'diterima',
    ]);
});
