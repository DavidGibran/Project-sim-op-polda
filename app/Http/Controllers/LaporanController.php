<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Perbaikan;
use App\Models\Penugasan;
use App\Models\MasterKend;
use App\Models\LaporanKerusakan;
use App\Exports\PerbaikanExport;
use App\Exports\KerusakanExport;

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
        $search = $request->query('search', '');
        $perPage = 25;
        $periode = $request->query('periode', 'all');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        // Logic periode preset
        if ($periode === 'this_month') {
            $tanggalDari = now()->startOfMonth()->toDateString();
            $tanggalSampai = now()->endOfMonth()->toDateString();
        } elseif ($periode === 'last_month') {
            $tanggalDari = now()->subMonth()->startOfMonth()->toDateString();
            $tanggalSampai = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($periode === 'this_year') {
            $tanggalDari = now()->startOfYear()->toDateString();
            $tanggalSampai = now()->endOfYear()->toDateString();
        } elseif ($periode === 'all') {
            $tanggalDari = null;
            $tanggalSampai = null;
        }

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
            'periode',
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
        $periode = $request->query('periode', 'all');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        if ($periode === 'this_month') {
            $tanggalDari = now()->startOfMonth()->toDateString();
            $tanggalSampai = now()->endOfMonth()->toDateString();
        } elseif ($periode === 'last_month') {
            $tanggalDari = now()->subMonth()->startOfMonth()->toDateString();
            $tanggalSampai = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($periode === 'this_year') {
            $tanggalDari = now()->startOfYear()->toDateString();
            $tanggalSampai = now()->endOfYear()->toDateString();
        } elseif ($periode === 'all') {
            $tanggalDari = null;
            $tanggalSampai = null;
        }

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

            $logs = $this->getPemakaianQuery($search, $tanggalDari, $tanggalSampai)->get();

            $filename = 'laporan-pemakaian-' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(
                new class($logs) implements \Maatwebsite\Excel\Concerns\FromView, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithStyles {
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

                    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
                    {
                        $highestRow = $sheet->getHighestRow();
                        $highestCol = $sheet->getHighestColumn();

                        if ($highestRow >= 9) {
                            $sheet->getStyle('A9:' . $highestCol . $highestRow)->applyFromArray([
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '00000000'],
                                    ],
                                ],
                            ]);
                            
                            $sheet->getStyle('A9:' . $highestCol . '9')->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                ],
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                ],
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'startColor' => [
                                        'argb' => 'FFFFD966',
                                    ],
                                ],
                            ]);
                        }
                    }
                },
                $filename
            );
        }

        /**
         * ===============================
         * EXPORT KERUSAKAN
         * ===============================
         */
        if ($type === 'kerusakan') {
            $filename = 'laporan-kerusakan-' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(
                new KerusakanExport($tanggalDari, $tanggalSampai, $search),
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
        $periode = $request->query('periode', 'all');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        if ($periode === 'this_month') {
            $tanggalDari = now()->startOfMonth()->toDateString();
            $tanggalSampai = now()->endOfMonth()->toDateString();
        } elseif ($periode === 'last_month') {
            $tanggalDari = now()->subMonth()->startOfMonth()->toDateString();
            $tanggalSampai = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($periode === 'this_year') {
            $tanggalDari = now()->startOfYear()->toDateString();
            $tanggalSampai = now()->endOfYear()->toDateString();
        } elseif ($periode === 'all') {
            $tanggalDari = null;
            $tanggalSampai = null;
        }

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

            $logs = $this->getPemakaianQuery($search, $tanggalDari, $tanggalSampai)->get();

            $pdf = Pdf::loadView('admin.laporan.pemakaian.pdf', [
                'logs' => $logs,
                'search' => $search,
                'tanggalDari' => $tanggalDari,
                'tanggalSampai' => $tanggalSampai,
                'printedAt' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-pemakaian-' . now()->format('Ymd_His') . '.pdf');
        }

        /**
         * ===============================
         * EXPORT KERUSAKAN
         * ===============================
         */
        if ($type === 'kerusakan') {
            $kerusakans = $this->getKerusakanQuery($search, $tanggalDari, $tanggalSampai)->get();

            $pdf = Pdf::loadView('admin.laporan.kerusakan.pdf', [
                'kerusakans' => $kerusakans,
                'search' => $search,
                'tanggalDari' => $tanggalDari,
                'tanggalSampai' => $tanggalSampai,
                'printedAt' => now(),
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('laporan-kerusakan-' . now()->format('Ymd_His') . '.pdf');
        }

        abort(404);
    }

    public function pemakaian(Request $request)
    {
        $search = $request->query('search', '');
        $perPage = 25;
        $periode = $request->query('periode', 'all');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        // Logic periode preset
        if ($periode === 'this_month') {
            $tanggalDari = now()->startOfMonth()->toDateString();
            $tanggalSampai = now()->endOfMonth()->toDateString();
        } elseif ($periode === 'last_month') {
            $tanggalDari = now()->subMonth()->startOfMonth()->toDateString();
            $tanggalSampai = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($periode === 'this_year') {
            $tanggalDari = now()->startOfYear()->toDateString();
            $tanggalSampai = now()->endOfYear()->toDateString();
        } elseif ($periode === 'all') {
            $tanggalDari = null;
            $tanggalSampai = null;
        }

        /**
         * Data tabel laporan pemakaian
         * Sumber data dari tb_penugasans yang sudah selesai
         */
        $logs = Penugasan::with('kendaraan')
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan', 'selesai', 'dibatalkan'])

            // Filter pencarian
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pengemudi', 'like', '%' . $search . '%')
                        ->orWhere('tujuan', 'like', '%' . $search . '%')
                        ->orWhere('catatan', 'like', '%' . $search . '%')
                        ->orWhereHas('kendaraan', function($k) use ($search) {
                            $k->where('no_polisi', 'like', '%' . $search . '%')
                                ->orWhere('merk', 'like', '%' . $search . '%')
                                ->orWhere('tipe', 'like', '%' . $search . '%');
                        });
                });
            })

            // Filter tanggal tugas
            ->when($tanggalDari, fn($q) => $q->whereDate('tgl_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tgl_tugas', '<=', $tanggalSampai))

            ->orderByDesc('tgl_tugas')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * Chart 1: Tren pemakaian per bulan (Berdasarkan tgl_tugas)
         */
        $monthlyData = Penugasan::selectRaw("
                DATE_FORMAT(tgl_tugas, '%Y-%m') as bulan,
                COUNT(*) as total
            ")
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan', 'selesai', 'dibatalkan'])
            ->when($tanggalDari, fn($q) => $q->whereDate('tgl_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tgl_tugas', '<=', $tanggalSampai))
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
        $jenisData = DB::table('tb_penugasans')
            ->join('master_kends', 'tb_penugasans.id_kend', '=', 'master_kends.id_kend')
            ->selectRaw('master_kends.jenis_kendaraan, COUNT(*) as total')
            ->whereIn('tb_penugasans.status', ['diterbitkan', 'diterima', 'berjalan', 'selesai', 'dibatalkan'])
            ->when($tanggalDari, fn($q) => $q->whereDate('tb_penugasans.tgl_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tb_penugasans.tgl_tugas', '<=', $tanggalSampai))
            ->groupBy('master_kends.jenis_kendaraan')
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
            'periode',
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

    /**
     * Halaman laporan kerusakan
     */
    public function kerusakan(Request $request)
    {
        $search = $request->query('search', '');
        $perPage = 25;
        $periode = $request->query('periode', 'all');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        if ($periode === 'this_month') {
            $tanggalDari = now()->startOfMonth()->toDateString();
            $tanggalSampai = now()->endOfMonth()->toDateString();
        } elseif ($periode === 'last_month') {
            $tanggalDari = now()->subMonth()->startOfMonth()->toDateString();
            $tanggalSampai = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($periode === 'this_year') {
            $tanggalDari = now()->startOfYear()->toDateString();
            $tanggalSampai = now()->endOfYear()->toDateString();
        } elseif ($periode === 'all') {
            $tanggalDari = null;
            $tanggalSampai = null;
        }

        $kerusakans = $this->getKerusakanQuery($search, $tanggalDari, $tanggalSampai)
            ->paginate($perPage)
            ->withQueryString();

        // Chart status kerusakan
        $statusData = LaporanKerusakan::select('status', DB::raw('COUNT(*) as total'))
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_lapor', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_lapor', '<=', $tanggalSampai))
            ->groupBy('status')
            ->get();

        $damageStatusChart = [
            'labels' => $statusData->pluck('status')->map(fn($item) => ucfirst($item))->values()->toArray(),
            'series' => $statusData->pluck('total')->values()->toArray(),
        ];

        // Chart trend per bulan
        $monthlyData = LaporanKerusakan::selectRaw("
                DATE_FORMAT(tanggal_lapor, '%Y-%m') as bulan,
                COUNT(*) as total
            ")
            ->when($tanggalDari, fn($q) => $q->whereDate('tanggal_lapor', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tanggal_lapor', '<=', $tanggalSampai))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $damageTrendChart = [
            'categories' => $monthlyData->pluck('bulan')->toArray(),
            'series' => $monthlyData->pluck('total')->toArray(),
        ];

        return view('admin.laporan.kerusakan.lapKerusakan', compact(
            'kerusakans',
            'search',
            'perPage',
            'periode',
            'tanggalDari',
            'tanggalSampai',
            'damageStatusChart',
            'damageTrendChart'
        ));
    }

    /**
     * Query reusable untuk laporan kerusakan
     */
    protected function getKerusakanQuery(?string $search, ?string $tanggalDari, ?string $tanggalSampai): Builder
    {
        return LaporanKerusakan::query()
            ->with('kendaraan')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('keluhan', 'like', '%' . $search . '%')
                        ->orWhere('no_laporan', 'like', '%' . $search . '%')
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

    /**
     * Query reusable untuk laporan pemakaian
     */
    protected function getPemakaianQuery(?string $search, ?string $tanggalDari, ?string $tanggalSampai): Builder
    {
        return Penugasan::query()
            ->with('kendaraan')
            ->whereIn('status', ['diterbitkan', 'diterima', 'berjalan', 'selesai', 'dibatalkan'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pengemudi', 'like', '%' . $search . '%')
                        ->orWhere('tujuan', 'like', '%' . $search . '%')
                        ->orWhere('catatan', 'like', '%' . $search . '%')
                        ->orWhereHas('kendaraan', function($k) use ($search) {
                            $k->where('no_polisi', 'like', '%' . $search . '%')
                                ->orWhere('merk', 'like', '%' . $search . '%')
                                ->orWhere('tipe', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($tanggalDari, fn($q) => $q->whereDate('tgl_tugas', '>=', $tanggalDari))
            ->when($tanggalSampai, fn($q) => $q->whereDate('tgl_tugas', '<=', $tanggalSampai))
            ->orderByDesc('tgl_tugas')
            ->orderByDesc('id');
    }
}
