<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PetugasController;

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

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.pdf');
    Route::get('/kepala/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.detail');

    // ================= PETUGAS =================
    Route::middleware(['auth'])->group(function () {
        // LIST PETUGAS
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');

        // TAMBAH PETUGAS
        Route::get('/petugas/tambah', [PetugasController::class, 'create'])->name('petugas.create');
        Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');

        // EDIT / UPDATE PETUGAS
        Route::put('/petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');

        // HAPUS PETUGAS
        Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
    });

    // ================= PROFILE =================
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
