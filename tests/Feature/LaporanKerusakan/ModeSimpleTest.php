<?php

use App\Models\MasterKend;
use App\Models\Penugasan;

test('vehicle can submit a simple damage report during assignment', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Dipakai']);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'berjalan',
    ]);

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.laporan-kerusakan.store'), [
            'mode' => 'simple',
            'keluhan' => 'Mesin mengalami panas berlebih',
            'nomor_hp' => '081234567890',
        ])->assertRedirect(route('kendaraan.dashboard'));

    $this->assertDatabaseHas('tb_laporan_kerusakans', [
        'id_kend' => $kendaraan->id_kend,
        'id_penugasan' => $penugasan->id,
        'sumber' => 'kendaraan',
        'mode' => 'simple',
        'keluhan' => 'Mesin mengalami panas berlebih',
        'nomor_hp' => '081234567890',
        'status' => 'diterbitkan',
    ]);
    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'dibatalkan',
    ]);
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Tersedia',
    ]);
});

test('simple report without phone number is not persisted', function () {
    $kendaraan = MasterKend::factory()->create();

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.laporan-kerusakan.store'), [
            'mode' => 'simple',
            'keluhan' => 'Ban bocor',
        ])->assertSessionHasErrors('nomor_hp');

    $this->assertDatabaseMissing('tb_laporan_kerusakans', [
        'id_kend' => $kendaraan->id_kend,
        'keluhan' => 'Ban bocor',
    ]);
});
