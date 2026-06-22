<?php

use App\Models\LaporanKerusakan;
use App\Models\MasterKend;
use App\Models\Perbaikan;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
});

test('admin can complete an active repair', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Perbaikan']);
    $laporan = LaporanKerusakan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diproses',
    ]);
    $perbaikan = Perbaikan::factory()->create([
        'id_laporan' => $laporan->id,
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diproses',
    ]);

    $this->put(route('perbaikan.update', $perbaikan), [
        'action' => 'selesai',
        'tgl_selesai' => '2026-06-22',
        'biaya' => 750000,
    ])->assertRedirect(route('perbaikan.riwayat'));

    $this->assertDatabaseHas('tb_perbaikans', [
        'id' => $perbaikan->id,
        'status' => 'selesai',
        'biaya' => 750000,
    ]);
    $this->assertDatabaseHas('tb_laporan_kerusakans', [
        'id' => $laporan->id,
        'status' => 'selesai',
    ]);
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Tersedia',
    ]);
});

test('repair in invalid state cannot be completed', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Perbaikan']);
    $laporan = LaporanKerusakan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diterbitkan',
    ]);
    $perbaikan = Perbaikan::factory()->create([
        'id_laporan' => $laporan->id,
        'id_kend' => $kendaraan->id_kend,
        'status' => 'dilaporkan',
    ]);

    $this->from(route('perbaikan.aktif'))
        ->put(route('perbaikan.update', $perbaikan), [
            'action' => 'selesai',
            'tgl_selesai' => '2026-06-22',
            'biaya' => 750000,
        ])->assertRedirect(route('perbaikan.aktif'))
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('tb_perbaikans', [
        'id' => $perbaikan->id,
        'status' => 'selesai',
    ]);
});
