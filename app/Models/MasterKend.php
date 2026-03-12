<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKend extends Model
{
    protected $table = 'master_kends';

    protected $primaryKey = 'id_kend';

    protected $fillable = [
        'no_polisi',
        'merk',
        'tipe',
        'tahun',
        'kategori_kendaraan',
        'jenis_kendaraan',
        'keterangan_penggunaan',
        'status',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function penugasans()
    {
        return $this->hasMany(Penugasan::class, 'id_kend');
    }

    public function perbaikans()
    {
        return $this->hasMany(Perbaikan::class, 'id_kend');
    }
}
