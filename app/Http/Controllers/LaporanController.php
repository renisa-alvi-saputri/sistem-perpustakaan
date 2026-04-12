<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan utama.
     */
    public function index()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('status', 'selesai')
            ->get();

        return view('laporan.index', compact('peminjaman'));
    }

    /**
     * Menampilkan detail laporan per peminjaman.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with('user', 'buku')->findOrFail($id);

        return view('laporan.laporan_detail', compact('peminjaman'));
    }

    /**
     * Laporan peminjaman (status dipinjam) dengan filter tanggal.
     */
    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with('user', 'buku')
            ->where('status', 'dipinjam');

        // Filter berdasarkan tgl_pinjam
        if ($request->filled('dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->sampai);
        }

        $peminjaman = $query->get();
        $dari       = $request->dari;
        $sampai     = $request->sampai;

        return view('laporan.peminjaman', compact('peminjaman', 'dari', 'sampai'));
    }

    /**
     * Laporan pengembalian (status selesai) dengan filter tanggal.
     */
    public function pengembalian(Request $request)
    {
        $query = Peminjaman::with('user', 'buku')
            ->where('status', 'selesai');

        // Filter berdasarkan tgl_kembali
        if ($request->filled('dari')) {
            $query->whereDate('tgl_kembali', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tgl_kembali', '<=', $request->sampai);
        }

        $peminjaman = $query->get();
        $dari       = $request->dari;
        $sampai     = $request->sampai;

        return view('laporan.pengembalian', compact('peminjaman', 'dari', 'sampai'));
    }
}
