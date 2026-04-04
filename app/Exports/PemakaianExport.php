<?php

namespace App\Exports;

use App\Models\Log;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PemakaianExport implements FromView
{
    protected $tanggalDari;
    protected $tanggalSampai;
    protected $search;

    /**
     * Constructor menerima filter dari controller
     */
    public function __construct($tanggalDari = null, $tanggalSampai = null, $search = null)
    {
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai;
        $this->search = $search;
    }

    /**
     * Menggunakan view untuk export (lebih fleksibel)
     */
    public function view(): View
    {
        $logs = Log::query()
            ->where('modul', 'log_pemakaian')

            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_tugas', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_pengemudi', 'like', '%' . $this->search . '%')
                        ->orWhere('nopol', 'like', '%' . $this->search . '%')
                        ->orWhere('tujuan', 'like', '%' . $this->search . '%');
                });
            })

            ->when($this->tanggalDari, fn($q) => $q->whereDate('tanggal_tugas', '>=', $this->tanggalDari))
            ->when($this->tanggalSampai, fn($q) => $q->whereDate('tanggal_tugas', '<=', $this->tanggalSampai))

            ->orderByDesc('tanggal_tugas')
            ->get();

        return view('exports.pemakaian', [
            'logs' => $logs
        ]);
    }
}