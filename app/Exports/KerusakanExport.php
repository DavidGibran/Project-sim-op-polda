<?php

namespace App\Exports;

use App\Models\LaporanKerusakan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KerusakanExport implements FromView, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected ?string $tanggalDari = null,
        protected ?string $tanggalSampai = null,
        protected ?string $search = null
    ) {}

    protected function query(): Builder
    {
        return LaporanKerusakan::query()
            ->with('kendaraan')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('keluhan', 'like', '%' . $this->search . '%')
                        ->orWhere('no_laporan', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kendaraan', function ($kendaraan) {
                            $kendaraan->where('no_polisi', 'like', '%' . $this->search . '%')
                                ->orWhere('merk', 'like', '%' . $this->search . '%')
                                ->orWhere('tipe', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->tanggalDari, function ($query) {
                $query->whereDate('tanggal_lapor', '>=', $this->tanggalDari);
            })
            ->when($this->tanggalSampai, function ($query) {
                $query->whereDate('tanggal_lapor', '<=', $this->tanggalSampai);
            })
            ->orderByDesc('tanggal_lapor');
    }

    public function view(): View
    {
        return view('admin.laporan.kerusakan.excel', [
            'kerusakans' => $this->query()->get()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Apply borders to table
        // The table data starts at row 8 based on the template
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
            
            // Header row styling
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
}
