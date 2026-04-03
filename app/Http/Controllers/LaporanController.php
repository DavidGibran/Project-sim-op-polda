<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Perbaikan;
use App\Exports\PerbaikanExport;

// include private method - service
use App\Services\PerbaikanServices;

class LaporanController extends Controller
{
    /**
     * Inject service
     */
    public function __construct(protected PerbaikanServices $service) {}

    /**
     * Redirect default ke laporan perbaikan
     */
    public function index()
    {
        return redirect()->route('laporan.perbaikan');
    }

    /**
     * Halaman laporan perbaikan
     *
     * Yang dipertahankan:
     * - tabel laporan
     * - chart status
     * - chart trend
     *
     * Yang ditambahkan:
     * - filter tanggal
     */
    public function perbaikan(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        // Data tabel laporan
        $perbaikans = $this->getPerbaikanQuery($search, $tanggalDari, $tanggalSampai)
            ->paginate($perPage)
            ->withQueryString();

        // Chart status perbaikan
        $statusData = Perbaikan::select('status', DB::raw('COUNT(*) as total'))
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_lapor', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_lapor', '<=', $tanggalSampai))
            ->groupBy('status')
            ->get();

        $repairStatusChart = [
            'labels' => $statusData->pluck('status')
                ->map(fn($item) => ucfirst($item))
                ->values()
                ->toArray(),
            'series' => $statusData->pluck('total')
                ->values()
                ->toArray(),
        ];

        // Chart trend per bulan
        $monthlyData = Perbaikan::selectRaw("
                DATE_FORMAT(tanggal_lapor, '%Y-%m') as bulan,
                COUNT(*) as total
            ")
            ->where('status', 'selesai')
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_lapor', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_lapor', '<=', $tanggalSampai))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $repairTrendChart = [
            'categories' => $monthlyData->pluck('bulan')->toArray(),
            'series' => $monthlyData->pluck('total')->toArray(),
        ];

        return view('admin.laporan.perbaikan.lapPerbaikan', compact(
            'perbaikans',
            'search',
            'perPage',
            'tanggalDari',
            'tanggalSampai',
            'repairStatusChart',
            'repairTrendChart'
        ));
    }

    /**
     * Export Excel laporan perbaikan
     */
    public function exportExcel(Request $request, string $type)
    {
        $search = $request->query('search');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        /**
         * ===============================
         * EXPORT PERBAIKAN
         * ===============================
         */
        if ($type === 'perbaikan') {
            $filename = 'laporan-perbaikan-' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(
                new PerbaikanExport($tanggalDari, $tanggalSampai, $search),
                $filename
            );
        }

        /**
         * ===============================
         * EXPORT PEMAKAIAN
         * ===============================
         */
        if ($type === 'pemakaian') {

            $logs = \App\Models\Log::query()
                ->where('modul', 'log_pemakaian')
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('kode_tugas', 'like', "%$search%")
                            ->orWhere('nama_pengemudi', 'like', "%$search%");
                    });
                })
                ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_tugas', '>=', $tanggalDari))
                ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_tugas', '<=', $tanggalSampai))
                ->get();

            $filename = 'laporan-pemakaian-' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(
                new class($logs) implements \Maatwebsite\Excel\Concerns\FromView {
                    private $logs;

                    public function __construct($logs)
                    {
                        $this->logs = $logs;
                    }

                    public function view(): \Illuminate\Contracts\View\View
                    {
                        return view('admin.laporan.pemakaian.excel', [
                            'logs' => $this->logs
                        ]);
                    }
                },
                $filename
            );
        }

        abort(404);
    }

    /**
     * Preview / export PDF laporan perbaikan
     */
    public function exportPdf(Request $request, string $type)
    {
        $search = $request->query('search');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        /**
         * ===============================
         * EXPORT PERBAIKAN
         * ===============================
         */
        if ($type === 'perbaikan') {

            $perbaikans = $this->getPerbaikanQuery($search, $tanggalDari, $tanggalSampai)->get();

            $pdf = Pdf::loadView('admin.laporan.perbaikan.pdf', [
                'perbaikans' => $perbaikans,
                'search' => $search,
                'tanggalDari' => $tanggalDari,
                'tanggalSampai' => $tanggalSampai,
                'printedAt' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-perbaikan-' . now()->format('Ymd_His') . '.pdf');
        }

        /**
         * ===============================
         * EXPORT PEMAKAIAN
         * ===============================
         */
        if ($type === 'pemakaian') {

            $logs = \App\Models\Log::query()
                ->where('modul', 'log_pemakaian')
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('kode_tugas', 'like', "%$search%")
                            ->orWhere('nama_pengemudi', 'like', "%$search%");
                    });
                })
                ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_tugas', '>=', $tanggalDari))
                ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_tugas', '<=', $tanggalSampai))
                ->get();

            $pdf = Pdf::loadView('admin.laporan.pemakaian.pdf', [
                'logs' => $logs,
                'search' => $search,
                'tanggalDari' => $tanggalDari,
                'tanggalSampai' => $tanggalSampai,
                'printedAt' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-pemakaian-' . now()->format('Ymd_His') . '.pdf');
        }

        abort(404);
    }

    public function pemakaian(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        /**
         * Data tabel laporan pemakaian
         *
         * Sumber data dari tb_logs
         * dan hanya mengambil modul log_pemakaian
         */
        $logs = \App\Models\Log::query()
            ->where('modul', 'log_pemakaian')

            // Filter pencarian
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_tugas', 'like', '%' . $search . '%')
                        ->orWhere('nama_pengemudi', 'like', '%' . $search . '%')
                        ->orWhere('nopol', 'like', '%' . $search . '%')
                        ->orWhere('tujuan', 'like', '%' . $search . '%')
                        ->orWhere('tipe_kendaraan', 'like', '%' . $search . '%')
                        ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%')
                        ->orWhere('catatan', 'like', '%' . $search . '%');
                });
            })

            // Filter tanggal tugas
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_tugas', '<=', $tanggalSampai))

            ->orderByDesc('tanggal_tugas')
            ->orderByDesc('id_log')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * Chart 1: Tren pemakaian per bulan
         */
        $monthlyData = \App\Models\Log::selectRaw("
                DATE_FORMAT(tanggal_tugas, '%Y-%m') as bulan,
                COUNT(*) as total
            ")
            ->where('modul', 'log_pemakaian')
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_tugas', '<=', $tanggalSampai))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $usageTrendChart = [
            'categories' => $monthlyData->pluck('bulan')->toArray(),
            'series' => $monthlyData->pluck('total')->toArray(),
        ];

        /**
         * Chart 2: Distribusi jenis kendaraan
         */
        $jenisData = \App\Models\Log::selectRaw("
                jenis_kendaraan,
                COUNT(*) as total
            ")
            ->where('modul', 'log_pemakaian')
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_tugas', '<=', $tanggalSampai))
            ->groupBy('jenis_kendaraan')
            ->get();

        $usageTypeChart = [
            'labels' => $jenisData->pluck('jenis_kendaraan')
                ->map(fn($item) => $item ?: 'Tidak diketahui')
                ->values()
                ->toArray(),
            'series' => $jenisData->pluck('total')
                ->values()
                ->toArray(),
        ];

        return view('admin.laporan.pemakaian.lapPemakaian', compact(
            'logs',
            'search',
            'perPage',
            'tanggalDari',
            'tanggalSampai',
            'usageTrendChart',
            'usageTypeChart'
        ));
    }

    /**
     * Query reusable untuk laporan perbaikan
     *
     * Dipakai oleh:
     * - halaman laporan
     * - export excel
     * - export pdf
     */
    protected function getPerbaikanQuery(?string $search, ?string $tanggalDari, ?string $tanggalSampai): Builder
    {
        return Perbaikan::query()
            ->with('kendaraan')
            ->where('status', 'selesai')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('keluhan', 'like', '%' . $search . '%')
                        ->orWhereHas('kendaraan', function ($kendaraan) use ($search) {
                            $kendaraan->where('no_polisi', 'like', '%' . $search . '%')
                                ->orWhere('merk', 'like', '%' . $search . '%')
                                ->orWhere('tipe', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($tanggalDari, function ($query) use ($tanggalDari) {
                $query->whereDate('tanggal_lapor', '>=', $tanggalDari);
            })
            ->when($tanggalSampai, function ($query) use ($tanggalSampai) {
                $query->whereDate('tanggal_lapor', '<=', $tanggalSampai);
            })
            ->orderByDesc('tanggal_lapor');
    }
}
