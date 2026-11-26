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

    // UI-003 & UI-004: Barang Masuk
    Route::get('/barang-masuk', [IncomingTransactionController::class, 'index'])->name('incoming.index');
    Route::get('/barang-masuk/create', [IncomingTransactionController::class, 'create'])->name('incoming.create');
    Route::post('/barang-masuk', [IncomingTransactionController::class, 'store'])->name('incoming.store');
    Route::get('/barang-masuk/{transaction}', [IncomingTransactionController::class, 'show'])->name('incoming.show');
    Route::put('/barang-masuk/{transaction}', [IncomingTransactionController::class, 'update'])->name('incoming.update');
    Route::post('/barang-masuk/{transaction}/validate', [IncomingTransactionController::class, 'validateTransaction'])->name('incoming.validate');
    Route::post('/barang-masuk/{transaction}/cancel', [IncomingTransactionController::class, 'cancel'])->name('incoming.cancel');

    // UI-005 & UI-006: Barang Keluar
    Route::get('/barang-keluar', [OutgoingTransactionController::class, 'index'])->name('outgoing.index');
    Route::get('/barang-keluar/create', [OutgoingTransactionController::class, 'create'])->name('outgoing.create');
    Route::post('/barang-keluar', [OutgoingTransactionController::class, 'store'])->name('outgoing.store');
    Route::get('/barang-keluar/{transaction}', [OutgoingTransactionController::class, 'show'])->name('outgoing.show');
    Route::put('/barang-keluar/{transaction}', [OutgoingTransactionController::class, 'update'])->name('outgoing.update');
    Route::post('/barang-keluar/{transaction}/validate', [OutgoingTransactionController::class, 'validateTransaction'])->name('outgoing.validate');
    Route::post('/barang-keluar/{transaction}/cancel', [OutgoingTransactionController::class, 'cancel'])->name('outgoing.cancel');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
