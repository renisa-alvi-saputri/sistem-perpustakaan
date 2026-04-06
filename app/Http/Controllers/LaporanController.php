<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class LaporanController extends Controller
{
    // Tampilkan halaman laporan
    public function index()
    {
        // Ambil data peminjaman yang statusnya selesai
        $peminjaman = Peminjaman::with('buku')
                        ->where('status', 'selesai')
                        ->get();

        return view('laporan.index', compact('peminjaman'));
    }
}
