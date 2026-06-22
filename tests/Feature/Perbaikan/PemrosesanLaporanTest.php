<?php

use App\Models\LaporanKerusakan;
use App\Models\MasterKend;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
});

test('admin can process a damage report into repair', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Tersedia']);
    $laporan = LaporanKerusakan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'keluhan' => 'Rem tidak berfungsi optimal',
        'status' => 'diterbitkan',
    ]);

    $this->post(route('perbaikan.store'), [
        'id_laporan' => $laporan->id,
        'teknisi' => 'Teknisi Internal',
        'biaya' => 250000,
        'tgl_mulai' => '2026-06-22',
        'detail_perbaikan' => 'Pemeriksaan dan penggantian kampas rem',
    ])->assertRedirect(route('perbaikan.aktif'));

    $this->assertDatabaseHas('tb_perbaikans', [
        'id_laporan' => $laporan->id,
        'id_kend' => $kendaraan->id_kend,
        'status' => 'diproses',
        'teknisi' => 'Teknisi Internal',
        'biaya' => 250000,
        'catatan' => 'Pemeriksaan dan penggantian kampas rem',
    ]);
    $this->assertDatabaseHas('tb_laporan_kerusakans', [
        'id' => $laporan->id,
        'status' => 'diproses',
    ]);
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Perbaikan',
    ]);
});

test('unknown report cannot create a repair', function () {
    $this->post(route('perbaikan.store'), [
        'id_laporan' => 999999,
        'teknisi' => 'Teknisi Internal',
        'tgl_mulai' => '2026-06-22',
        'detail_perbaikan' => 'Pemeriksaan',
    ])->assertSessionHasErrors('id_laporan');

    $this->assertDatabaseMissing('tb_perbaikans', [
        'id_laporan' => 999999,
    ]);
});
