<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\MasterKendController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['title' => 'Dashboard']);
    })->name('dashboard');

    // Route::resource('users', UserController::class);
    // Hanya Admin
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';

Route::resource('kendaraan', MasterKendController::class);

Route::resource('penugasan', PenugasanController::class);

Route::resource('perbaikan', PerbaikanController::class);

Route::get('log', [LogController::class,'index']);
