<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Halaman log pemakaian admin
     *
     * Menampilkan data perjalanan yang sudah selesai
     * dan sudah tercatat ke tb_logs.
     */
    public function index(Request $request)
    {
        /**
         * Ambil query string filter
         */
        $search = $request->query('search');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');
        $perPage = (int) $request->query('per_page', 10);

        /**
         * Query utama log pemakaian
         *
         * Kita filter berdasarkan modul = log_pemakaian
         * agar log admin/system lain tidak ikut tampil.
         */
        $logs = Log::query()
            ->where('modul', 'log_pemakaian')

            /**
             * Filter pencarian
             *
             * Bisa cari berdasarkan:
             * - kode_tugas
             * - nama_pengemudi
             * - nopol
             * - tujuan
             * - jenis_kendaraan
             * - tipe_kendaraan
             */
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_tugas', 'like', '%' . $search . '%')
                        ->orWhere('nama_pengemudi', 'like', '%' . $search . '%')
                        ->orWhere('nopol', 'like', '%' . $search . '%')
                        ->orWhere('tujuan', 'like', '%' . $search . '%')
                        ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%')
                        ->orWhere('tipe_kendaraan', 'like', '%' . $search . '%');
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
             * Urutkan dari data terbaru
             */
            ->orderByDesc('tanggal_tugas')
            ->orderByDesc('id_log')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * Render view log admin
         */
        return view('admin.log.index', compact(
            'logs',
            'search',
            'tanggalDari',
            'tanggalSampai',
            'perPage'
        ));
    }
}