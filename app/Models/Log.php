<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'tb_logs';

    protected $primaryKey = 'id_log';

    /**
     * Field yang boleh diisi mass assignment
     *
     * Tetap mempertahankan field log lama,
     * lalu ditambah field log pemakaian.
     */
    protected $fillable = [
        // field log lama
        'id_user',
        'aksi',
        'modul',
        'deskripsi',
        'ip_address',

        // field log pemakaian
        'penugasan_id',
        'id_kend',
        'kode_tugas',
        'tanggal_tugas',
        'nama_pengemudi',
        'nopol',
        'jenis_kendaraan',
        'tipe_kendaraan',
        'tujuan',
        'km_awal',
        'km_akhir',
        'foto_odometer',
        'waktu_mulai',
        'waktu_selesai',
        'catatan',
    ];

    /**
     * Casting data
     *
     * Supaya field tanggal / waktu otomatis dikenali Laravel.
     */
    protected $casts = [
        'tanggal_tugas' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Relasi ke user admin
     *
     * Dipakai untuk log sistem / admin lama.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(MasterKend::class, 'id_kend', 'id_kend');
    }

    /**
     * Relasi ke penugasan
     */
    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'penugasan_id', 'id');
    }
}