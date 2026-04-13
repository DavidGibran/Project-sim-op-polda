<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Halaman log pemakaian admin
     *
     * Menampilkan riwayat semua penugasan kecuali yang sedang berjalan.
     */
    public function index(Request $request)
    {
        /**
         * Ambil query string filter
         */
        $search = $request->query('search');
        $periode = $request->query('periode', 'all');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');
        $perPage = (int) $request->query('per_page', 10);
        
        $sortBy = $request->query('sort_by', 'id');
        $order = $request->query('order', 'desc');

        /**
         * Query utama log pemakaian menggunakan model Penugasan
         */
        $query = Penugasan::with('kendaraan')
            ->where('status', '!=', 'berjalan');

        /**
         * Filter Pencarian
         */
        $query->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('pengemudi', 'like', '%' . $search . '%')
                    ->orWhere('tujuan', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhereHas('kendaraan', function($vk) use ($search) {
                        $vk->where('no_polisi', 'like', '%' . $search . '%')
                            ->orWhere('merk', 'like', '%' . $search . '%')
                            ->orWhere('tipe', 'like', '%' . $search . '%');
                    });
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
         * Sorting Logic
         */
        $allowedSorts = ['id', 'pengemudi', 'tgl_tugas', 'km_awal', 'km_akhir', 'tujuan'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $order);
        } elseif ($sortBy === 'tipe_kendaraan') {
            $query->join('master_kends', 'tb_penugasans.id_kend', '=', 'master_kends.id_kend')
                  ->orderBy('master_kends.tipe', $order)
                  ->select('tb_penugasans.*'); // Avoid column collision
        } else {
            $query->orderBy('id', 'desc');
        }

        $logs = $query->paginate($perPage)->withQueryString();

        /**
         * Render view log admin dengan data penugasan
         */
        return view('admin.log.index', compact(
            'logs',
            'search',
            'periode',
            'tanggalDari',
            'tanggalSampai',
            'perPage',
            'sortBy',
            'order'
        ));
    }
}