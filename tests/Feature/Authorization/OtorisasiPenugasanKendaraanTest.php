<?php

use App\Models\MasterKend;
use App\Models\Penugasan;

test('vehicle cannot accept another vehicles assignment', function () {
    $kendaraanLogin = MasterKend::factory()->create();
    $kendaraanPemilik = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraanPemilik->id_kend,
        'status' => 'diterbitkan',
    ]);

    $this->withKendaraanSession($kendaraanLogin)
        ->post(route('kendaraan.penugasan.terima', $penugasan))
        ->assertForbidden();

    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'id_kend' => $kendaraanPemilik->id_kend,
        'status' => 'diterbitkan',
    ]);
    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'diterima',
    ]);
});

test('vehicle cannot complete another vehicles assignment', function () {
    $kendaraanLogin = MasterKend::factory()->create();
    $kendaraanPemilik = MasterKend::factory()->create(['status' => 'Dipakai']);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraanPemilik->id_kend,
        'status' => 'berjalan',
        'km_awal' => 10000,
    ]);

    $this->withKendaraanSession($kendaraanLogin)
        ->post(route('kendaraan.penugasan.selesai', $penugasan), [
            'km_akhir' => 10100,
        ])->assertForbidden();

    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'selesai',
    ]);
});
