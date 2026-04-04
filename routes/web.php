<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

// Redirect default ke login
Route::redirect('/', '/login');

// ================= DASHBOARD =================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// ================= ROUTE AUTHENTIKASI =================
Route::middleware('auth')->group(function () {

    // ================= MASTER DATA =================
    // Kategori
    Route::resource('kategori', KategoriController::class);

    // Buku
    Route::resource('buku', BukuController::class);

    // Anggota
    Route::resource('anggota', AnggotaController::class);

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);

    // ================= PENGEMBALIAN PETUGAS =================
    // Halaman pengembalian untuk petugas
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->name('pengembalian.index');

    // Simpan pengembalian oleh petugas
    Route::post('/pengembalian', [PeminjamanController::class, 'storePengembalian'])
        ->name('pengembalian.store');

    // ================= PENGEMBALIAN ANGGOTA =================
    // Halaman pengembalian khusus anggota
    Route::get('/pengembalian-anggota', [PeminjamanController::class, 'pengembalianAnggota'])
        ->name('pengembalian.anggota');

    // Simpan pengembalian oleh anggota
    Route::post('/pengembalian-anggota', [PeminjamanController::class, 'storePengembalianAnggota'])
        ->name('pengembalian.anggota.store');

    // ================= RIWAYAT PEMINJAMAN =================
    Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])
        ->name('riwayat');

    //konfirmasi peminjaman oleh petugas
    Route::put('/peminjaman/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasi'])
    ->name('peminjaman.konfirmasi');

    //konfirmasi pengembalian oleh petugas
    Route::put('/peminjaman/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasi'])
    ->name('peminjaman.konfirmasi');

    // Konfirmasi pengembalian oleh petugas
    Route::put('/peminjaman/selesai/{id}', [PeminjamanController::class, 'selesai'])
    ->name('peminjaman.selesai');

    // ================= PROFILE =================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
