<?php

namespace App\Exports;

use App\Models\Perbaikan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export Excel untuk laporan perbaikan
 *
 * Class ini khusus dipakai untuk export data laporan perbaikan.
 * Filter yang dipakai:
 * - search
 * - tanggal_dari
 * - tanggal_sampai
 */
class PerbaikanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Constructor untuk menerima filter dari controller
     */
    public function __construct(
        protected ?string $tanggalDari = null,
        protected ?string $tanggalSampai = null,
        protected ?string $search = null
    ) {}

    /**
     * Ambil collection data yang akan diexport
     */
    public function collection()
    {
        return $this->query()->get();
    }

    /**
     * Query utama export
     *
     * Dibuat terpisah agar mudah dirawat.
     */
    protected function query(): Builder
    {
        return Perbaikan::query()
            ->with('kendaraan')
            ->where('status', 'selesai')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('keluhan', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kendaraan', function ($kendaraan) {
                            $kendaraan->where('no_polisi', 'like', '%' . $this->search . '%')
                                ->orWhere('merk', 'like', '%' . $this->search . '%')
                                ->orWhere('tipe', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->tanggalDari, function ($query) {
                // Filter tanggal awal berdasarkan tanggal laporan
                $query->whereDate('tanggal_lapor', '>=', $this->tanggalDari);
            })
            ->when($this->tanggalSampai, function ($query) {
                // Filter tanggal akhir berdasarkan tanggal laporan
                $query->whereDate('tanggal_lapor', '<=', $this->tanggalSampai);
            })
            ->orderByDesc('tanggal_lapor');
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'No Polisi',
            'Merk',
            'Tipe',
            'Keluhan',
            'Tanggal Lapor',
            'Tanggal Selesai',
            'Biaya',
            'Status',
        ];
    }

    /**
     * Mapping data per row ke format Excel
     */
    public function map($perbaikan): array
    {
        static $no = 1;

        return [
            $no++,
            $perbaikan->kendaraan->no_polisi ?? '-',
            $perbaikan->kendaraan->merk ?? '-',
            $perbaikan->kendaraan->tipe ?? '-',
            $perbaikan->keluhan ?? '-',
            $perbaikan->tanggal_lapor
                ? Carbon::parse($perbaikan->tanggal_lapor)->format('d-m-Y')
                : '-',
            $perbaikan->tgl_selesai
                ? Carbon::parse($perbaikan->tgl_selesai)->format('d-m-Y')
                : '-',
            (int) ($perbaikan->biaya ?? 0),
            ucfirst($perbaikan->status ?? '-'),
        ];
    }
}