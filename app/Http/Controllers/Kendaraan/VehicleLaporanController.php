<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleLaporanController extends Controller
{
    /**
     * Form tambah laporan (Mode Simple - Pengemudi)
     */
    public function create(Request $request)
    {
        $kendaraanId = session('kendaraan_id');
        $kendaraan = MasterKend::findOrFail($kendaraanId);

        // Cari penugasan aktif jika ada
        $penugasanAktif = Penugasan::where('id_kend', $kendaraanId)
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan'])
            ->first();

        $mode = $request->query('mode');

        // Jika ada penugasan aktif, paksa mode simple
        if ($penugasanAktif) {
            $mode = 'simple';
        } elseif (!$mode) {
            // Jika tidak ada penugasan aktif, langsung ke mode detail
            $mode = 'detail';
        }

        return view('user.laporan-kerusakan.create', compact('kendaraan', 'penugasanAktif', 'mode'));
    }


    /**
     * Simpan laporan dari kendaraan
     */
    public function store(Request $request)
    {
        $mode = $request->input('mode', 'simple');

        $rules = [
            'keluhan' => 'required|string',
        ];

        if ($mode == 'simple') {
            $rules['nomor_hp'] = 'required|string';
        } else {
            $rules['detail_teknis'] = 'nullable|string';
        }

        $request->validate($rules);

        $kendaraanId = session('kendaraan_id');
        $kendaraan = MasterKend::findOrFail($kendaraanId);

        // Cari penugasan aktif
        $penugasanAktif = Penugasan::where('id_kend', $kendaraanId)
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan'])
            ->first();

        // Security check: Detail mode only allowed if no active assignment
        if ($penugasanAktif && $mode == 'detail') {
            return back()->with('error', 'Mode detail tidak tersedia saat dalam penugasan aktif.');
        }

        try {
            DB::beginTransaction();

            // Create report with retry logic for unique no_laporan
            $maxRetries = 5;
            $attempt = 0;
            $reportCreated = false;

            while ($attempt < $maxRetries && !$reportCreated) {
                try {
                    LaporanKerusakan::create([
                        'no_laporan' => LaporanKerusakan::generateNoLaporan(),
                        'id_kend' => $kendaraanId,
                        'id_penugasan' => $penugasanAktif ? $penugasanAktif->id : null,
                        'sumber' => 'kendaraan',
                        'mode' => $mode,
                        'keluhan' => $request->keluhan,
                        'detail_teknis' => $request->detail_teknis,
                        'nomor_hp' => $mode == 'simple' ? $request->nomor_hp : null,
                        'status' => 'diterbitkan',
                        'tanggal_lapor' => now(),
                    ]);
                    $reportCreated = true;
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($e->errorInfo[1] == 1062) {
                        $attempt++;
                        if ($attempt >= $maxRetries) throw $e;
                        usleep(100000);
                    } else {
                        throw $e;
                    }
                }
            }



            // Jika ada penugasan aktif, batalkan
            if ($penugasanAktif) {
                $penugasanAktif->update([
                    'status' => 'dibatalkan',
                    'catatan' => ($penugasanAktif->catatan ? $penugasanAktif->catatan . "\n" : "") . "Dibatalkan otomatis karena laporan kerusakan."
                ]);
            }

            // Status kendaraan tetap Tersedia (sesuai instruksi)
            $kendaraan->update(['status' => 'Tersedia']);

            DB::commit();

            return redirect()->route('kendaraan.dashboard')
                ->with('success', 'Laporan kerusakan berhasil dikirim. Admin akan segera menghubungi Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage())->withInput();
        }
    }
}
