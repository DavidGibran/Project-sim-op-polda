<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $table = 'tb_penugasans';

    protected $primaryKey = 'id';

    /**
     * Field yang boleh diisi mass assignment
     */
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

        // Field tambahan untuk flow perjalanan
        'waktu_mulai',
        'waktu_selesai',
        'foto_odometer',
    ];

    /**
     * Casting tipe data
     *
     * Supaya field datetime otomatis diperlakukan sebagai object tanggal.
     */
    protected $casts = [
        'tgl_tugas' => 'date',
        'tgl_selesai' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Relasi ke kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(MasterKend::class, 'id_kend');
    }
}