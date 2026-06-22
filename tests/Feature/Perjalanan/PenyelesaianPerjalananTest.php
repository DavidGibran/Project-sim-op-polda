<?php

use App\Models\MasterKend;
use App\Models\Penugasan;

test('vehicle can complete an active trip', function () {
    $kendaraan = MasterKend::factory()->create([
        'status' => 'Dipakai',
        'km_terakhir' => 10000,
    ]);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'berjalan',
        'km_awal' => 10000,
        'waktu_mulai' => now()->subHour(),
    ]);

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.penugasan.selesai', $penugasan), [
            'km_akhir' => 10125,
            'catatan' => 'Perjalanan selesai dengan aman',
        ])->assertRedirect(route('kendaraan.riwayat-pemakaian'));

    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'selesai',
        'km_akhir' => 10125,
        'catatan' => 'Perjalanan selesai dengan aman',
    ]);
    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'berjalan',
    ]);
    expect($penugasan->refresh()->waktu_selesai)->not->toBeNull();
    $this->assertDatabaseHas('master_kends', [
        'id_kend' => $kendaraan->id_kend,
        'status' => 'Tersedia',
        'km_terakhir' => 10125,
    ]);
});
