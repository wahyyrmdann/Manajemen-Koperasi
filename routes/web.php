<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingTransactionController;
use App\Http\Controllers\OutgoingTransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    // UI-002: Dashboard (Halaman Utama)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // UI-003: Daftar Barang Masuk (hanya halaman list)
    Route::get('/barang-masuk', [IncomingTransactionController::class, 'index'])->name('incoming.index');

    // UI-005: Daftar Barang Keluar (hanya halaman list)
    Route::get('/barang-keluar', [OutgoingTransactionController::class, 'index'])->name('outgoing.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
