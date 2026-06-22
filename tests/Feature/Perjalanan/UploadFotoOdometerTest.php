<?php

use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
});

test('vehicle can upload odometer photo when completing trip', function () {
    $kendaraan = MasterKend::factory()->create(['status' => 'Dipakai']);
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'berjalan',
        'km_awal' => 30000,
    ]);

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.penugasan.selesai', $penugasan), [
            'km_akhir' => 30100,
            'foto_odometer' => UploadedFile::fake()->image('odometer.jpg'),
        ])->assertRedirect(route('kendaraan.riwayat-pemakaian'));

    $path = $penugasan->refresh()->foto_odometer;

    expect($path)->toStartWith('odometer/');
    Storage::disk('local')->assertExists($path);
    $this->assertDatabaseHas('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'selesai',
        'foto_odometer' => $path,
    ]);
});

test('non image odometer upload is rejected', function () {
    $kendaraan = MasterKend::factory()->create();
    $penugasan = Penugasan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'berjalan',
        'km_awal' => 30000,
    ]);

    $this->withKendaraanSession($kendaraan)
        ->post(route('kendaraan.penugasan.selesai', $penugasan), [
            'km_akhir' => 30100,
            'foto_odometer' => UploadedFile::fake()->create('odometer.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors('foto_odometer');

    $this->assertDatabaseMissing('tb_penugasans', [
        'id' => $penugasan->id,
        'status' => 'selesai',
    ]);
    Storage::disk('local')->assertDirectoryEmpty('odometer');
});
