<?php

namespace App\Http\Controllers\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\Log;
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
        $riwayatPemakaian = Log::query()
            ->where('modul', 'log_pemakaian')
            ->where('id_kend', $kendaraan->id_kend)

            /**
             * Filter search
             *
             * Bisa cari berdasarkan:
             * - kode_tugas
             * - nama_pengemudi
             * - nopol
             * - tujuan
             * - jenis_kendaraan
             * - tipe_kendaraan
             * - catatan
             */
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_tugas', 'like', '%' . $search . '%')
                        ->orWhere('nama_pengemudi', 'like', '%' . $search . '%')
                        ->orWhere('nopol', 'like', '%' . $search . '%')
                        ->orWhere('tujuan', 'like', '%' . $search . '%')
                        ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%')
                        ->orWhere('tipe_kendaraan', 'like', '%' . $search . '%')
                        ->orWhere('catatan', 'like', '%' . $search . '%');
                });
            })

            /**
             * Filter tanggal tugas
             */
            ->when($tanggalDari, function ($query) use ($tanggalDari) {
                $query->whereDate('tanggal_tugas', '>=', $tanggalDari);
            })

            ->when($tanggalSampai, function ($query) use ($tanggalSampai) {
                $query->whereDate('tanggal_tugas', '<=', $tanggalSampai);
            })

            /**
             * Urutkan dari yang terbaru
             */
            ->orderByDesc('tanggal_tugas')
            ->orderByDesc('id_log')
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