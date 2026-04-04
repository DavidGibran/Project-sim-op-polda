<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use App\Models\MasterKend;
use Illuminate\Http\Request;

class RiwayatPemakaianController extends Controller
{
    /**
     * Menampilkan riwayat pemakaian kendaraan yang sedang login
     *
     * Sumber data:
     * - tb_logs
     *
     * Filter:
     * - hanya modul log_pemakaian
     * - hanya milik kendaraan yang sedang login
     */
    public function index(Request $request)
    {
        /**
         * Ambil id kendaraan dari session login
         */
        $kendaraanId = session('kendaraan_id');

        /**
         * Pastikan kendaraan yang login valid
         */
        $kendaraan = MasterKend::find($kendaraanId);

        if (! $kendaraan) {
            return redirect()->route('login')
                ->with('error', 'Session kendaraan tidak valid. Silakan login ulang.');
        }

        /**
         * Ambil query filter dari request
         */
        $search = $request->query('search');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');
        $perPage = (int) $request->query('per_page', 10);

        /**
         * Query utama riwayat pemakaian
         *
         * Hanya ambil:
         * - modul log_pemakaian
         * - id_kend sesuai kendaraan login
         */
        $riwayatPemakaian = Penugasan::query()
            ->where('id_kend', $kendaraan->id_kend)
            ->where('status', 'selesai')

            /**
             * Filter search
             */
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tujuan', 'like', '%' . $search . '%')
                        ->orWhere('pengemudi', 'like', '%' . $search . '%')
                        ->orWhere('catatan', 'like', '%' . $search . '%');
                });
            })

            /**
             * Filter tanggal tugas
             */
            ->when($tanggalDari, function ($query) use ($tanggalDari) {
                $query->whereDate('tgl_tugas', '>=', $tanggalDari);
            })

            ->when($tanggalSampai, function ($query) use ($tanggalSampai) {
                $query->whereDate('tgl_tugas', '<=', $tanggalSampai);
            })

            /**
             * Urutkan dari yang terbaru
             */
            ->orderByDesc('tgl_tugas')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * Render view riwayat pemakaian user
         */
        return view('user.riwayatPemakaian', compact(
            'kendaraan',
            'riwayatPemakaian',
            'search',
            'tanggalDari',
            'tanggalSampai',
            'perPage'
        ));
    }
}