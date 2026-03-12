<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'tb_logs';

    protected $primaryKey = 'id_log';

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class,'id_tugas');
    }
}
