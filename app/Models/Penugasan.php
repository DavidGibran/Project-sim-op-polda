<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $table = 'tb_penugasans';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_kend',
        'pengemudi',
        'tgl_tugas',
        'tgl_selesai',
        'tujuan',
        'km_awal',
        'km_akhir',
        'catatan',
        'status',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(MasterKend::class, 'id_kend');
    }
}
