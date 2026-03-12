<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $table = 'tb_penugasans';

    protected $primaryKey = 'id_tugas';

    protected $fillable = [
        'id_kend',
        'nama_sopir',
        'tgl_tugas',
        'tujuan',
        'kegiatan',
        'odo_awal',
        'odo_akhir',
        'prj_mulai',
        'prj_selesai',
        'foto_odo',
        'note',
        'status',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(MasterKend::class, 'id_kend');
    }
}
