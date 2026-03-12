<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKend extends Model
{
    protected $primaryKey = 'id_kend';

    protected $fillable = [
        'no_polisi',
        'kategori_kend',
        'jenis_kend',
        'merk',
        'tahun',
        'km_terakhir',
        'status'
    ];

    public function penugasans()
    {
        return $this->hasMany(Penugasan::class,'id_kend');
    }

    public function perbaikans()
    {
        return $this->hasMany(Perbaikan::class,'id_kend');
    }
}
