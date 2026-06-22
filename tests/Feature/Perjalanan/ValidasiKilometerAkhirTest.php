<?php

use App\Models\MasterKend;
use App\Models\Penugasan;

test('ending kilometer cannot be lower than starting kilometer', function () {
    $kendaraan = MasterKend::factory()->create([
        'status' => 'Dipakai',
        'km_terakhir' => 20000,
    ]);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'berjalan',
        'km_awal' => 20000,
    ]);

    $this->withKendaraanSession($kendaraan)
        ->from(route('kendaraan.perjalanan-aktif'))
        ->post(route('kendaraan.penugasan.selesai', $penugasan), [
            'km_akhir' => 19999,
        ])->assertRedirect(route('kendaraan.perjalanan-aktif'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'berjalan',
        'km_akhir' => null,
    ]);
    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'selesai',
    ]);
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'km_terakhir' => 20000,
    ]);
});

test('ending kilometer is required', function () {
    $kendaraan = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'berjalan',
    ]);

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.penugasan.selesai', $penugasan))
        ->assertSessionHasErrors('km_akhir');

    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'selesai',
    ]);
});
