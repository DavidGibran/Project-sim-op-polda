<?php

use App\Models\MasterKend;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
});

test('admin can issue an assignment for an available vehicle', function () {
    $kendaraan = MasterKend::factory()->create([
        'status' => 'Tersedia',
        'km_terakhir' => 12500,
    ]);

    $this->post(route('penugasan.store'), [
        'id_kend' => $kendaraan->id_kend,
        'tujuan' => 'Mapolda Jawa Timur',
        'pengemudi' => 'Budi',
        'tgl_tugas' => '2026-06-22',
        'catatan' => 'Operasional',
    ])->assertRedirect(route('penugasan.index'));

    $this->assertDatabaseHas('tb_penugasans', [
        'id_kend' => $kendaraan->id_kend,
        'tujuan' => 'Mapolda Jawa Timur',
        'km_awal' => 12500,
        'status' => 'diterbitkan',
    ]);
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Tersedia',
    ]);
});

test('invalid assignment is not persisted', function () {
    $this->post(route('penugasan.store'), [
        'id_kend' => 999999,
        'tujuan' => '',
        'tgl_tugas' => 'not-a-date',
    ])->assertSessionHasErrors(['id_kend', 'tujuan', 'tgl_tugas']);

    $this->assertDatabaseMissing('tb_penugasans', [
        'id_kend' => 999999,
    ]);
});
