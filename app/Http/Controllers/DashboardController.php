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

        // Data Kendaraan Tidak Digunakan Terlama (Top 3)
        $kendaraanTidakDigunakan = MasterKend::leftJoin('tb_penugasans', 'master_kends.id_kend', '=', 'tb_penugasans.id_kend')
            ->select(
                'master_kends.id_kend',
                'master_kends.no_polisi',
                'master_kends.merk',
                'master_kends.tipe',
                DB::raw('MAX(tb_penugasans.tgl_tugas) as tgl_terakhir')
            )
            ->groupBy('master_kends.id_kend', 'master_kends.no_polisi', 'master_kends.merk', 'master_kends.tipe')
            ->orderBy('tgl_terakhir', 'asc') // NULL (belum pernah) akan muncul pertama
            ->take(3)
            ->get();

        // Data Tambahan untuk Modal (R2/R4)
        $totalR2 = MasterKend::where('kategori_kendaraan', 'R2')->count();
        $totalR4 = MasterKend::where('kategori_kendaraan', 'R4')->count();
        
        // Data Tambahan untuk Modal Aktif
        $siapDipakai = MasterKend::where('status', 'Tersedia')->count();
        $sedangTugas = Penugasan::whereIn('status', ['berjalan'])->count();

        // Data Tabel Aktivitas Terbaru (Penugasan)
        $penugasanTerbaru = Penugasan::with('kendaraan')
            ->latest()
            ->take(5)
            ->get();

        // Data Riwayat Perbaikan Terbaru (Top 5) - Untuk Live Feed
        $perbaikanTerbaru = Perbaikan::with('kendaraan')
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        // Data Perbaikan Aktif (Top 10) - Khusus untuk Modal Maintenance
        $perbaikanAktif = Perbaikan::with('kendaraan')
            ->where('status', 'diproses')
            ->latest('tanggal_lapor')
            ->take(10)
            ->get();

        // Data Komposisi Armada untuk Visualisasi
        $komposisiKategori = [
            'R2' => MasterKend::where('kategori_kendaraan', 'R2')->count(),
            'R4' => MasterKend::where('kategori_kendaraan', 'R4')->count(),
            'R6' => MasterKend::where('kategori_kendaraan', 'R6')->count(),
            'Lainnya' => MasterKend::whereNotIn('kategori_kendaraan', ['R2', 'R4', 'R6'])->count(),
        ];

        $komposisiJenis = [
            'RANUM' => MasterKend::where('jenis_kendaraan', 'RANUM')->count(),
            'RANSUS' => MasterKend::where('jenis_kendaraan', 'RANSUS')->count(),
            'LAINNYA' => MasterKend::where('jenis_kendaraan', 'LAINNYA')->count(),
        ];

        $distribusiBBM = MasterKend::select('bbm', DB::raw('count(*) as total'))
            ->whereNotNull('bbm')
            ->where('bbm', '!=', '-')
            ->groupBy('bbm')
            ->orderBy('total', 'desc')
            ->pluck('total', 'bbm')
            ->toArray();

        $statusArmada = [
            'Tersedia' => $siapDipakai,
            'Dipakai' => $kendaraanAktif,
            'Perbaikan' => $kendaraanPerbaikan,
        ];

        // --- NEW METRICS FOR ENHANCED MODALS ---
        
        // 1. Total Kendaraan Extras
        $avgOdometer = round(MasterKend::avg('km_terakhir') ?? 0, 0);
        $oldestVehicle = MasterKend::min('tahun');
        $newestVehicle = MasterKend::max('tahun');
        
        // 2. Kendaraan Aktif Extras
        $utilizationRate = $totalKendaraan > 0 ? round(($kendaraanAktif / $totalKendaraan) * 100, 1) : 0;
        $topVehicles = MasterKend::withCount('penugasans')
            ->orderBy('penugasans_count', 'desc')
            ->take(3)
            ->get();
            
        // 3. Kendaraan Perbaikan Extras
        $avgRepairDuration = Perbaikan::whereNotNull('tgl_selesai')
            ->whereNotNull('tgl_mulai')
            ->select(DB::raw('AVG(DATEDIFF(tgl_selesai, tgl_mulai)) as avg_days'))
            ->first()->avg_days ?? 0;
        $avgRepairDuration = (int) round($avgRepairDuration, 0);
        
        $oldestRepair = Perbaikan::with('kendaraan')
            ->whereNull('tgl_selesai')
            ->orderBy('tanggal_lapor', 'asc')
            ->first();
            
        // 4. Penugasan Aktif Extras
        $assignmentsToday = Penugasan::whereDate('created_at', now()->toDateString())->count();
        $assignmentsFinishedToday = Penugasan::whereDate('tgl_selesai', now()->toDateString())
            ->where('status', 'selesai')
            ->count();
        
        $oldestAssignment = Penugasan::with('kendaraan')
            ->whereIn('status', ['berjalan', 'diterima'])
            ->orderBy('tgl_tugas', 'asc')
            ->first();

        return view('dashboard', compact(
            'totalKendaraan',
            'kendaraanAktif',
            'kendaraanPerbaikan',
            'penugasanAktif',
            'kendaraanTidakDigunakan',
            'totalR2',
            'totalR4',
            'siapDipakai',
            'sedangTugas',
            'penugasanTerbaru',
            'perbaikanTerbaru',
            'perbaikanAktif',
            'komposisiKategori',
            'komposisiJenis',
            'distribusiBBM',
            'statusArmada',
            // New variables
            'avgOdometer',
            'oldestVehicle',
            'newestVehicle',
            'utilizationRate',
            'topVehicles',
            'avgRepairDuration',
            'oldestRepair',
            'assignmentsToday',
            'assignmentsFinishedToday',
            'oldestAssignment'
        ))->with('title', 'Dashboard');
    }
}
