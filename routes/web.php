<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterKendController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\KendaraanImportController;
use App\Http\Controllers\Auth\VehicleAuthController;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/auth/login', [VehicleAuthController::class, 'login'])->name('login.universal');
Route::post('/auth/logout', [VehicleAuthController::class, 'logout'])->name('logout.universal');

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        | Dashboard
        */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        /*
        | Import Kendaraan
        */
        Route::get('kendaraan/import', [KendaraanImportController::class, 'index'])
            ->name('kendaraan.import');

        Route::post('kendaraan/import', [KendaraanImportController::class, 'import'])
            ->name('kendaraan.import.post');

        /*
        | Master Data
        */
        Route::resource('users', UserController::class);
        Route::resource('kendaraan', MasterKendController::class);

        /*
        | Penugasan Kendaraan
        */
        Route::resource('penugasan', PenugasanController::class);

        Route::post(
            'penugasan/{penugasan}/batalkan',
            [PenugasanController::class, 'batalkan']
        )->name('penugasan.batalkan');

        /*
        | Perbaikan Kendaraan
        */
        Route::resource('perbaikan', PerbaikanController::class);

        /*
        | Log Pemakaian
        */
        Route::get('log', [LogController::class, 'index'])
            ->name('log.index');
    });

/*
|--------------------------------------------------------------------------
| USER / KENDARAAN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['kendaraan'])
    ->prefix('kendaraan')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard-kendaraan');
        })->name('kendaraan.dashboard');

    });

require __DIR__.'/auth.php';