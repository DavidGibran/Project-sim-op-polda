<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;
use App\Models\MasterKend;
use App\Models\Perbaikan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama (Fleet Metrics)
        $totalKendaraan = MasterKend::count();
        $kendaraanAktif = MasterKend::where('status', 'Dipakai')->count();
        $kendaraanPerbaikan = MasterKend::where('status', 'Perbaikan')->count();
        $penugasanAktif = Penugasan::whereIn('status', ['diterbitkan', 'diterima', 'berjalan'])->count();

        // Data Grafik Distribusi (Donut)
        $tersediaCount = MasterKend::where('status', 'Tersedia')->count();
        $dipakaiCount = $kendaraanAktif;
        $perbaikanCount = $kendaraanPerbaikan;

        // Data Tabel Aktivitas Terbaru (Penugasan)
        $penugasanTerbaru = Penugasan::with('kendaraan')
            ->latest()
            ->take(5)
            ->get();

        // Data Riwayat Perbaikan Terbaru (3 Data)
        $perbaikanTerbaru = Perbaikan::with('kendaraan')
            ->latest('tanggal_lapor')
            ->take(3)
            ->get();

        // Data Trend Operasional (Line Chart - 12 Bulan)
        // Note: In real app, this would be grouped by month. 
        // For demonstration matching user requirements:
        $trendData = [
            'aktif' => [15, 18, 17, 20, 22, 21, 25, 24, 26, 28, 27, 30],
            'penugasan' => [10, 12, 11, 14, 15, 13, 18, 17, 19, 21, 20, 22],
            'perbaikan' => [2, 3, 2, 4, 3, 2, 5, 4, 3, 5, 4, 6]
        ];

        return view('dashboard', compact(
            'totalKendaraan',
            'kendaraanAktif',
            'kendaraanPerbaikan',
            'penugasanAktif',
            'tersediaCount',
            'dipakaiCount',
            'perbaikanCount',
            'penugasanTerbaru',
            'perbaikanTerbaru',
            'trendData'
        ))->with('title', 'Dashboard');
    }
}
