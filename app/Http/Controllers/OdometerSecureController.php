<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class OdometerSecureController extends Controller
{
    /**
     * Serve the odometer photo securely
     * 
     * Accessible by:
     * - Admins (role === 'admin')
     * - The driver/vehicle assigned to this penugasan
     */
    public function viewFoto(Penugasan $penugasan)
    {
        // 1. Authentication & Authorization Check
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        
        // Drivers login session-based using 'kendaraan_id'
        $isCurrentVehicle = session()->has('kendaraan_id') && session('kendaraan_id') == $penugasan->id_kend;

        if (!$isAdmin && !$isCurrentVehicle) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat foto odometer ini.');
        }

        // 2. Validate photo path exists in DB
        if (!$penugasan->foto_odometer) {
            abort(404, 'Foto odometer tidak ditemukan.');
        }

        $path = $penugasan->foto_odometer;

        // 3. Securely serve from private local storage (first priority for new uploads)
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->response($path);
        }

        // 4. Securely serve from public storage (fallback for older uploads)
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        abort(404, 'File foto odometer tidak ditemukan di server.');
    }
}
