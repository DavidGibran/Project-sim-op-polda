<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'tb_laporan_kerusakans';

    protected $fillable = [
        'no_laporan',
        'id_kend',
        'id_penugasan',
        'sumber',
        'mode',
        'keluhan',
        'detail_teknis',
        'nomor_hp',
        'status',
        'tanggal_lapor',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(MasterKend::class, 'id_kend');
    }

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'id_penugasan');
    }

    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'id_laporan');
    }

    /**
     * Helper untuk generate nomor laporan
     */
    public static function generateNoLaporan()
    {
        $prefix = 'REP-'.date('Ymd');

        // Cari nomor laporan terakhir hari ini berdasarkan urutan string terbesar
        $lastReport = self::where('no_laporan', 'like', $prefix.'-%')
            ->orderBy('no_laporan', 'desc')
            ->first();

        if ($lastReport) {
            // Ekstrak 4 digit terakhir
            $lastNumber = (int) substr($lastReport->no_laporan, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Mulai dari 0001 jika belum ada data hari ini
            $nextNumber = '0001';
        }

        return $prefix.'-'.$nextNumber;
    }
}
