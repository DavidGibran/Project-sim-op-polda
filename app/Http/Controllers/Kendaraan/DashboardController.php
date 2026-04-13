<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Update password untuk kendaraan yang sedang login
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $kendaraanId = session('kendaraan_id');
        $kendaraan = MasterKend::find($kendaraanId);

        if (! $kendaraan) {
            return back()->with('error', 'Sesi tidak valid.');
        }

        // Cek password saat ini
        if (! Hash::check($request->current_password, $kendaraan->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        // Update password
        $kendaraan->update([
            'password' => $request->new_password,
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}