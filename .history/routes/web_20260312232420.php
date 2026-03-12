<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\MasterKendController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\VehicleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/auth/login');
})->name('home');

Route::post('/auth/login', [VehicleAuthController::class, 'login'])->name('login.universal');
Route::post('/auth/logout', [VehicleAuthController::class, 'logout'])->name('logout.universal');

// Group Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('kendaraan/import', [App\Http\Controllers\KendaraanImportController::class, 'index'])->name('kendaraan.import');
    Route::post('kendaraan/import', [App\Http\Controllers\KendaraanImportController::class, 'import'])->name('kendaraan.import.post');

    Route::resource('users', UserController::class);
    Route::resource('kendaraan', MasterKendController::class);
    Route::resource('penugasan', PenugasanController::class);
    Route::resource('perbaikan', PerbaikanController::class);
    Route::get('log', [LogController::class, 'index'])->name('log.index');
});

// Group Kendaraan
Route::middleware(['kendaraan'])->prefix('kendaraan')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard-kendaraan');
    })->name('kendaraan.dashboard');
});

require __DIR__.'/auth.php';

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');
