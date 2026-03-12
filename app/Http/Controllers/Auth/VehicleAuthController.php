<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\MasterKend;

class VehicleAuthController extends Controller
{
    /**
     * Handle an incoming universal authentication request.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $inputUsername = $request->input('username');
        $inputPassword = $request->input('password');

        // 2. Cek sebagai Admin (tabel users) berdasarkan email
        $user = User::where('email', $inputUsername)->first();

        if ($user) {
            // Jika user admin ditemukan, verifikasi password
            if (Hash::check($inputPassword, $user->password)) {
                // Login Auth Laravel
                Auth::login($user);
                $request->session()->regenerate();
                
                // Redirect ke dashboard admin
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } else {
                // Jika password admin salah, langsung kembalikan error,
                // tanpa melanjutkan pengecekan ke tabel kendaraan.
                throw ValidationException::withMessages([
                    'username' => trans('auth.failed'),
                ]);
            }
        }

        // 3. Cek sebagai Kendaraan (tabel master_kends) 
        // Jika tidak ada di tabel users, lanjut cari di tabel kendaraan berdasarkan username atau no_polisi
        $kendaraan = MasterKend::where('username', $inputUsername)
            ->orWhere('no_polisi', $inputUsername)
            ->first();

        if ($kendaraan) {
            // Verifikasi password kendaraan
            if (Hash::check($inputPassword, $kendaraan->password)) {
                // Buat custom session untuk kendaraan
                session([
                    'kendaraan_id' => $kendaraan->id_kend,
                    'kendaraan_polisi' => $kendaraan->no_polisi,
                ]);
                $request->session()->regenerate();

                // Redirect ke dashboard khusus kendaraan
                // Asumsi routenya sementara akan dinamai 'dashboard'
                return redirect()->intended(route('kendaraan.dashboard', absolute: false)); 
            }
        }

        // 4. Jika kedua tabel gagal menemukan kredensial / password kendaraan salah
        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    /**
     * Handle logout universal for both admins and vehicles.
     */
    public function logout(Request $request)
    {
        // Logout Admin
        if (Auth::check()) {
            Auth::logout();
        }

        // Logout Kendaraan
        if ($request->session()->has('kendaraan_id')) {
            $request->session()->forget(['kendaraan_id', 'kendaraan_polisi']);
        }

        // Invalidasi & regenerasi token CSRF secara global untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirect ke login universal
    }
}
