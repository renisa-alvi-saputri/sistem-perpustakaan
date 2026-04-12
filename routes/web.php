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

// =====================================================================
// DEFAULT — redirect ke halaman login
// =====================================================================
Route::redirect('/', '/login');


// =====================================================================
// DASHBOARD
// =====================================================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


// =====================================================================
// SEMUA ROUTE DI BAWAH INI BUTUH LOGIN (middleware auth)
// =====================================================================
Route::middleware('auth')->group(function () {

    // -----------------------------------------------------------------
    // MASTER DATA
    // -----------------------------------------------------------------

    // Kategori buku (CRUD otomatis via resource)
    Route::resource('kategori', KategoriController::class);

    // Data buku (CRUD otomatis via resource)
    Route::resource('buku', BukuController::class);

    // Data anggota (CRUD otomatis via resource)
    Route::resource('anggota', AnggotaController::class);

    // Peminjaman (CRUD otomatis via resource)
    Route::resource('peminjaman', PeminjamanController::class);


    // -----------------------------------------------------------------
    // PENGEMBALIAN — PETUGAS
    // -----------------------------------------------------------------

    // Halaman daftar pengembalian untuk petugas
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->name('pengembalian.index');

    // Simpan data pengembalian oleh petugas
    Route::post('/pengembalian', [PeminjamanController::class, 'storePengembalian'])
        ->name('pengembalian.store');


    // -----------------------------------------------------------------
    // PENGEMBALIAN — ANGGOTA
    // -----------------------------------------------------------------

    // Halaman pengembalian khusus anggota
    Route::get('/pengembalian-anggota', [PeminjamanController::class, 'pengembalianAnggota'])
        ->name('pengembalian.anggota');

    // Simpan data pengembalian oleh anggota
    Route::post('/pengembalian-anggota', [PeminjamanController::class, 'storePengembalianAnggota'])
        ->name('pengembalian.anggota.store');


    // -----------------------------------------------------------------
    // RIWAYAT PEMINJAMAN
    // -----------------------------------------------------------------
    Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])
        ->name('riwayat');


    // -----------------------------------------------------------------
    // AKSI PEMINJAMAN (konfirmasi, tolak, selesai)
    // -----------------------------------------------------------------

    // Konfirmasi peminjaman oleh petugas
    Route::put('/peminjaman/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasi'])
        ->name('peminjaman.konfirmasi');

    // Konfirmasi peminjaman oleh petugas
    Route::put('/peminjaman/{id}/konfirmasi', [PeminjamanController::class, 'konfirmasi'])
        ->name('peminjaman.konfirmasi');

    // Tolak peminjaman oleh petugas
    Route::put('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])
        ->name('peminjaman.tolak');

    // Konfirmasi pengembalian selesai oleh petugas
    Route::put('/peminjaman/selesai/{id}', [PeminjamanController::class, 'selesai'])
        ->name('peminjaman.selesai');


    // -----------------------------------------------------------------
    // LAPORAN (khusus kepala)
    // -----------------------------------------------------------------

    // Halaman utama laporan
Route::get('/laporan', [LaporanController::class, 'index'])
    ->name('laporan.index');

// Download laporan dalam format PDF
Route::get('/laporan/pdf', [LaporanController::class, 'downloadPdf'])
    ->name('laporan.pdf');

// Detail laporan per peminjaman
Route::get('/kepala/laporan/{id}', [LaporanController::class, 'show'])
    ->name('laporan.detail');

// ✅ Route laporan peminjaman & pengembalian dipisah
Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])
    ->name('laporan.peminjaman');

Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])
    ->name('laporan.pengembalian');

    // -----------------------------------------------------------------
    // PETUGAS (khusus kepala)
    // -----------------------------------------------------------------
    Route::middleware(['auth'])->group(function () {

        // Daftar semua petugas
        Route::get('/petugas', [PetugasController::class, 'index'])
            ->name('petugas.index');

        // Form tambah petugas
        Route::get('/petugas/tambah', [PetugasController::class, 'create'])
            ->name('petugas.create');

        // Simpan petugas baru
        Route::post('/petugas', [PetugasController::class, 'store'])
            ->name('petugas.store');

        // Update data petugas
        Route::put('/petugas/{id}', [PetugasController::class, 'update'])
            ->name('petugas.update');

        // Hapus petugas
        Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])
            ->name('petugas.destroy');
    });


    // -----------------------------------------------------------------
    // PROFILE
    // -----------------------------------------------------------------

    // Halaman lihat profile
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    // Halaman edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Simpan perubahan profile
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Hapus akun
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


// =====================================================================
// ROUTE AUTENTIKASI (login, register, dll) — dari file auth.php
// =====================================================================
require __DIR__ . '/auth.php';
