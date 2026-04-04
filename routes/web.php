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
use App\Http\Controllers\LaporanController;

/**
 * Controller sisi pengemudi / kendaraan
 * 
 * Penempatan controller ini nanti ada di:
 * app/Http/Controllers/Kendaraan/
 */
use App\Http\Controllers\Kendaraan\DashboardController as KendaraanDashboardController;
use App\Http\Controllers\Kendaraan\PerjalananAktifController;
use App\Http\Controllers\Kendaraan\RiwayatPemakaianController;

// Library Export
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

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
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/auth/login', [VehicleAuthController::class, 'login'])->name('login.universal');
Route::post('/auth/logout', [VehicleAuthController::class, 'logout'])->name('logout.universal');

require __DIR__.'/auth.php';

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
        Route::get('perbaikan-aktif', [PerbaikanController::class, 'aktif'])->name('perbaikan.aktif');
        Route::get('perbaikan-riwayat', [PerbaikanController::class, 'riwayat'])->name('perbaikan.riwayat');
        Route::resource('perbaikan', PerbaikanController::class);

        /*
        | Log Pemakaian
        */
        Route::get('log', [LogController::class, 'index'])->name('log.index');
        
        /*
        | Laporan
        */
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('pemakaian',             [LaporanController::class, 'pemakaian'])->name('pemakaian');
            Route::get('perbaikan',             [LaporanController::class, 'perbaikan'])->name('perbaikan');
            Route::get('export/{type}/pdf',     [LaporanController::class, 'exportPdf'])->name('export.pdf');
            Route::get('export/{type}/excel',   [LaporanController::class, 'exportExcel'])->name('export.excel');
        });

        /*
        | Export
        */
        Route::get('/export-users', function () {
            return Excel::download(new UsersExport, 'users.xlsx');
        });
    });

/*
|--------------------------------------------------------------------------
| USER / KENDARAAN ROUTES
|--------------------------------------------------------------------------
|
| Area ini khusus untuk kendaraan / pengemudi.
| Login tetap menggunakan VehicleAuthController,
| lalu session kendaraan dicek oleh KendaraanMiddleware.
|
*/

Route::middleware(['kendaraan'])
    ->prefix('kendaraan')
    ->name('kendaraan.')
    ->group(function () {

        /*
        | Dashboard pengemudi
        */
        Route::get('/dashboard', [KendaraanDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        | Perjalanan aktif
        */
        Route::get('/perjalanan-aktif', [PerjalananAktifController::class, 'index'])
            ->name('perjalanan-aktif');

        /*
        | Aksi penugasan oleh pengemudi
        */
        Route::post('/penugasan/{penugasan}/terima', [PerjalananAktifController::class, 'terimaTugas'])
            ->name('penugasan.terima');

        Route::post('/penugasan/{penugasan}/mulai', [PerjalananAktifController::class, 'mulaiPerjalanan'])
            ->name('penugasan.mulai');

        Route::post('/penugasan/{penugasan}/selesai', [PerjalananAktifController::class, 'selesaikanPerjalanan'])
            ->name('penugasan.selesai');

        /*
        | Riwayat pemakaian saya
        */
        Route::get('/riwayat-pemakaian', [RiwayatPemakaianController::class, 'index'])
            ->name('riwayat-pemakaian');
    });