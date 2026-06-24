<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KendaraanTemplateExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            [
                'B 1234 ABC',
                'TOYOTA',
                'AVANZA',
                'AVANZA 1.3 G M/T',
                '2022',
                'PERTALITE',
                'R4',
                'RANUM',
                '15000',
                'Tersedia',
                'BRIPKA JOHN DOE',
                'KENDARAAN OPERASIONAL'
            ],
            [
                'B 5678 DEF',
                'HONDA',
                'Vario 150',
                'HONDA VARIO 150 CBS',
                '2021',
                'PERTAMAX',
                'R2',
                'RANUM',
                '0',
                'Tersedia',
                '-',
                '-'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NO_POLISI',
            'MERK',
            'TIPE',
            'NAMA_PADA_SIMAK',
            'TAHUN',
            'BBM',
            'KATEGORI_KENDARAAN',
            'JENIS_KENDARAAN',
            'KM_TERAKHIR',
            'STATUS_KENDARAAN',
            'NAMA_PEMEGANG',
            'KETERANGAN_PENGGUNAAN'
        ];
    }

    public function title(): string
    {
        return 'Template Import Kendaraan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
