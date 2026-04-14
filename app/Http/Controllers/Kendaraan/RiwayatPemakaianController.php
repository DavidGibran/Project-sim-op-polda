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
        $periode = $request->query('periode', 'all');
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
        $query = Penugasan::query()
            ->where('id_kend', $kendaraan->id_kend)
            ->where('status', 'selesai');

        /**
         * Filter search
         */
        $query->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('pengemudi', 'like', '%' . $search . '%')
                    ->orWhere('catatan', 'like', '%' . $search . '%');
            });
        });

        /**
         * Filter Periode
         */
        if ($periode === 'this_month') {
            $query->whereMonth('tgl_tugas', now()->month)
                  ->whereYear('tgl_tugas', now()->year);
        } elseif ($periode === 'last_month') {
            $query->whereMonth('tgl_tugas', now()->subMonth()->month)
                  ->whereYear('tgl_tugas', now()->subMonth()->year);
        } elseif ($periode === 'this_year') {
            $query->whereYear('tgl_tugas', now()->year);
        } elseif ($periode === 'custom' && $tanggalDari && $tanggalSampai) {
            $query->whereDate('tgl_tugas', '>=', $tanggalDari)
                  ->whereDate('tgl_tugas', '<=', $tanggalSampai);
        }

        /**
         * Urutkan dari yang terbaru
         */
        $riwayatPemakaian = $query->orderByDesc('tgl_tugas')
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
            'periode',
            'tanggalDari',
            'tanggalSampai',
            'perPage'
        ));
    }
}