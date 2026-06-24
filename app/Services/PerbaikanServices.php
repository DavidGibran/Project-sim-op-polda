<?php
// app/Services/PerbaikanService.php

namespace App\Services;

use App\Models\Perbaikan;

class PerbaikanServices
{
    public function getRiwayat(string $search = null, int $perPage = 10)
    {
        $query = Perbaikan::with('kendaraan')
            ->where('status', 'selesai');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('keluhan', 'like', "%{$search}%")
                  ->orWhere('teknisi', 'like', "%{$search}%")
                  ->orWhereHas('kendaraan', function ($qk) use ($search) {
                      $qk->where('no_polisi', 'like', "%{$search}%")
                         ->orWhere('merk', 'like', "%{$search}%")
                         ->orWhere('tipe', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest('tgl_selesai')->paginate($perPage)->withQueryString();
    }
}