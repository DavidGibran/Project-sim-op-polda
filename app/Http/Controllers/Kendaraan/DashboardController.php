<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard sisi pengemudi / kendaraan
     */
    public function index(Request $request)
    {
        // Ambil id kendaraan dari session
        $kendaraanId = session('kendaraan_id');

        // Ambil data kendaraan
        $kendaraan = MasterKend::find($kendaraanId);

        if (! $kendaraan) {
            return redirect()->route('login')
                ->with('error', 'Session kendaraan tidak valid. Silakan login ulang.');
        }

        /**
         * Ambil penugasan terbaru yang masih relevan
         *
         * Status yang dianggap masih aktif:
         * - diterbitkan
         * - berjalan
         */
        $penugasanAktif = Penugasan::query()
            ->where('id_kend', $kendaraan->id_kend)
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan'])
            ->orderByDesc('tgl_tugas')
            ->orderByDesc('id')
            ->first();

        /**
         * Siapkan data dashboard agar blade lebih rapi
         */
        $dashboardData = [
            'no_polisi' => $kendaraan->no_polisi,
            'merk' => $kendaraan->merk,
            'tipe' => $kendaraan->tipe,
            'nama_pengemudi' => $penugasanAktif->pengemudi ?? '-',
            'status_perjalanan' => $penugasanAktif->status ?? '-',
            'km_awal' => $penugasanAktif->km_awal ?? $kendaraan->km_terakhir ?? 0,
            'tujuan' => $penugasanAktif->tujuan ?? '-',
            'tanggal_tugas' => $penugasanAktif->tgl_tugas ?? null,

            /**
             * Ambil waktu mulai dari kolom baru
             * Jika belum ada, tampilkan null agar di blade menjadi "-"
             */
            'waktu_mulai' => $penugasanAktif?->waktu_mulai
                ? $penugasanAktif->waktu_mulai->format('d-m-Y H:i')
                : null,

            'bisa_terima_tugas' => $penugasanAktif && $penugasanAktif->status === 'diterbitkan',
            'penugasan_aktif' => $penugasanAktif,
        ];

        return view('user.dashboard', compact('kendaraan', 'penugasanAktif', 'dashboardData'));
    }
}