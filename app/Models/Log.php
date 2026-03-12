<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'tb_logs';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_user',
        'aksi',
        'modul',
        'deskripsi',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
