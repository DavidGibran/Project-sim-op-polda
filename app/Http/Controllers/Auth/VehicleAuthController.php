<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MasterKend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VehicleAuthController extends Controller
{
    /**
     * Handle login universal:
     * - admin login via tabel users
     * - kendaraan login via tabel master_kends
     */
    public function login(Request $request)
    {
        /**
         * Validasi input login
         */
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $inputUsername = $request->input('username');
        $inputPassword = $request->input('password');

        /**
         * ==========================================================
         * 1. Cek login sebagai ADMIN
         * ==========================================================
         *
         * Admin login menggunakan email pada tabel users.
         */
        $user = User::where('email', $inputUsername)->first();

        if ($user) {
            if (! Hash::check($inputPassword, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => trans('auth.failed'),
                ]);
            }

            /**
             * Login admin ke auth Laravel
             */
            Auth::login($user);

            /**
             * Regenerate session setelah login
             * untuk mencegah session fixation
             */
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        /**
         * ==========================================================
         * 2. Cek login sebagai KENDARAAN
         * ==========================================================
         *
         * Kendaraan login berdasarkan username atau no_polisi
         * pada tabel master_kends.
         */
        $kendaraan = MasterKend::where('username', $inputUsername)
            ->orWhere('no_polisi', $inputUsername)
            ->first();

        if ($kendaraan && Hash::check($inputPassword, $kendaraan->password)) {
            /**
             * Regenerate session dulu
             */
            $request->session()->regenerate();

            /**
             * Simpan session khusus kendaraan
             */
            $request->session()->put([
                'kendaraan_id' => $kendaraan->id_kend,
                'kendaraan_polisi' => $kendaraan->no_polisi,
            ]);

            return redirect()->intended(route('kendaraan.dashboard', absolute: false));
        }

        /**
         * Jika admin dan kendaraan sama-sama gagal login
         */
        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    /**
     * Logout universal
     * - admin
     * - kendaraan
     */
    public function logout(Request $request)
    {
        /**
         * Logout admin jika sedang login via Auth
         */
        if (Auth::check()) {
            Auth::logout();
        }

        /**
         * Hapus session kendaraan jika ada
         */
        $request->session()->forget([
            'kendaraan_id',
            'kendaraan_polisi',
        ]);

        /**
         * Invalidasi session lama dan buat token baru
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}