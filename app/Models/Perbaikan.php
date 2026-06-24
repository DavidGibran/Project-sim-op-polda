<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table = 'tb_perbaikans';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_kend',
        'id_laporan',
        'tanggal_lapor',
        'keluhan',
        'status',
        'teknisi',
        'tgl_mulai',
        'tgl_selesai',
        'biaya',
        'catatan',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }


    public function kendaraan()
    {
        return $this->belongsTo(MasterKend::class, 'id_kend');
    }
}
