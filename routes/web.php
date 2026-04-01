<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // ================= PENGEMBALIAN ANGGOTA =================
    Route::get('/pengembalian-anggota', [PeminjamanController::class, 'pengembalianAnggota'])
        ->name('pengembalian.anggota');

    Route::post('/pengembalian-anggota', [PeminjamanController::class, 'storePengembalianAnggota'])
        ->name('pengembalian.anggota.store');


    // ================= MASTER =================
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('anggota', AnggotaController::class);
    Route::resource('peminjaman', PeminjamanController::class);

    // Pengembalian oleh petugas
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->name('pengembalian.index');

    Route::post('/pengembalian', [PeminjamanController::class, 'storePengembalian'])
        ->name('pengembalian.store');


    // ================= PROFILE =================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
