<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Http\Request;

class PerjalananAktifController extends Controller
{
    /**
     * Halaman perjalanan aktif
     */
    public function index()
    {
        $kendaraanId = session('kendaraan_id');

        $kendaraan = MasterKend::find($kendaraanId);

        if (! $kendaraan) {
            return redirect()->route('login')
                ->with('error', 'Session kendaraan tidak valid.');
        }

        /**
         * Ambil penugasan aktif:
         * - diterbitkan
         * - diterima
         * - berjalan
         */
        $penugasan = Penugasan::query()
            ->where('id_kend', $kendaraan->id_kend)
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan'])
            ->orderByDesc('tgl_tugas')
            ->orderByDesc('id')
            ->first();

        return view('user.perjalananAktif', compact('kendaraan', 'penugasan'));
    }

    /**
     * TERIMA TUGAS
     *
     * diterbitkan -> diterima
     */
    public function terimaTugas(Penugasan $penugasan)
    {
        $this->authorizePenugasan($penugasan);

        if ($penugasan->status !== 'diterbitkan') {
            return back()->with('error', 'Tugas tidak valid.');
        }

        $penugasan->update([
            'status' => 'diterima',
        ]);

        // 🔥 update status kendaraan
        $penugasan->kendaraan()->update([
            'status' => 'Diterima',
        ]);

        return back()->with('success', 'Tugas berhasil diterima.');
    }

    /**
     * MULAI PERJALANAN
     *
     * diterima -> berjalan
     */
    public function mulaiPerjalanan(Penugasan $penugasan)
    {
        $this->authorizePenugasan($penugasan);

        if ($penugasan->status !== 'diterima') {
            return back()->with('error', 'Perjalanan belum bisa dimulai.');
        }

        // Cegah double klik
        if ($penugasan->waktu_mulai) {
            return back()->with('error', 'Perjalanan sudah dimulai.');
        }

        $penugasan->update([
            'status' => 'berjalan',
            'waktu_mulai' => now(),
        ]);

        // 🔥 update kendaraan
        $penugasan->kendaraan()->update([
            'status' => 'Perjalanan',
        ]);

        return back()->with('success', 'Perjalanan dimulai.');
    }

    /**
     * SELESAIKAN PERJALANAN
     */
    public function selesaikanPerjalanan(Request $request, Penugasan $penugasan)
    {
        $this->authorizePenugasan($penugasan);

        if ($penugasan->status !== 'berjalan') {
            return back()->with('error', 'Perjalanan tidak valid.');
        }

        $validated = $request->validate([
            'km_akhir' => ['required', 'numeric'],
            'foto_odometer' => ['required', 'image', 'max:4096'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $kmAwal = (int) ($penugasan->km_awal ?? 0);
        $kmAkhir = (int) $validated['km_akhir'];

        if ($kmAkhir < $kmAwal) {
            return back()->with('error', 'KM akhir tidak boleh lebih kecil dari KM awal.');
        }

        /**
         * Simpan foto
         */
        $fotoPath = $request->file('foto_odometer')
            ->store('odometer', 'public');

        /**
         * Update penugasan
         */
        $penugasan->update([
            'km_akhir' => $kmAkhir,
            'catatan' => $validated['catatan'] ?? null,
            'tgl_selesai' => now()->toDateString(),
            'waktu_selesai' => now(),
            'foto_odometer' => $fotoPath,
            'status' => 'selesai',
        ]);

        /**
         * Update kendaraan
         */
        $penugasan->kendaraan()->update([
            'km_terakhir' => $kmAkhir,
            'status' => 'Tersedia',
        ]);

        return redirect()
            ->route('kendaraan.riwayat-pemakaian')
            ->with('success', 'Perjalanan selesai.');
    }

    /**
     * AUTHORIZATION
     *
     * Pastikan kendaraan hanya akses datanya sendiri
     */
    protected function authorizePenugasan(Penugasan $penugasan): void
    {
        $kendaraanId = session('kendaraan_id');

        abort_unless(
            (int) $penugasan->id_kend === (int) $kendaraanId,
            403,
            'Akses ditolak.'
        );
    }
}