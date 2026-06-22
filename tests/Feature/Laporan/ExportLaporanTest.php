<?php

use App\Exports\KerusakanExport;
use App\Exports\PerbaikanExport;
use App\Models\LaporanKerusakan;
use App\Models\MasterKend;
use App\Models\Perbaikan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    Carbon::setTestNow('2026-06-22 10:30:00');
    Excel::fake();
});

afterEach(function () {
    Carbon::setTestNow();
});

test('admin can export completed repair report to excel', function () {
    $kendaraan = MasterKend::factory()->create();
    $laporan = LaporanKerusakan::factory()->create([
        'id_kend' => $kendaraan->id_kend,
        'status' => 'selesai',
    ]);
    $perbaikan = Perbaikan::factory()->create([
        'id_laporan' => $laporan->id,
        'id_kend' => $kendaraan->id_kend,
        'status' => 'selesai',
    ]);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('laporan.export.excel', ['type' => 'perbaikan']))
        ->assertOk();

    Excel::assertDownloaded('laporan-perbaikan-20260622_103000.xlsx', function (PerbaikanExport $export) {
        return true;
    });
    $this->assertDatabaseHas('tb_perbaikans', [
        'id' => $perbaikan->id,
        'status' => 'selesai',
    ]);
});

test('admin can export damage report to excel', function () {
    $laporan = LaporanKerusakan::factory()->create();

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('laporan.export.excel', ['type' => 'kerusakan']))
        ->assertOk();

    Excel::assertDownloaded('laporan-kerusakan-20260622_103000.xlsx', function (KerusakanExport $export) {
        return true;
    });
    $this->assertDatabaseMissing('tb_laporan_kerusakans', [
        'id' => $laporan->id,
        'status' => 'selesai',
    ]);
});

test('guest cannot export reports', function () {
    $this->get(route('laporan.export.excel', ['type' => 'perbaikan']))
        ->assertRedirect(route('login'));
});

test('unknown export type returns not found', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('laporan.export.excel', ['type' => 'unknown']))
        ->assertNotFound();
});
