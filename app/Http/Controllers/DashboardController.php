<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        // -----------------------------------------------------------------
        // DATA UMUM — dipakai di semua role
        // -----------------------------------------------------------------
        $jumlahBuku     = Buku::count();
        $jumlahKategori = Kategori::count();
        $jumlahAnggota  = User::where('role', 'anggota')->count();

        // Jumlah pinjaman berbeda tiap role
        $jumlahPinjaman = match($role) {
            'kepala'  => Peminjaman::where('status', 'selesai')->count(),
            'anggota' => Peminjaman::where('status', 'dipinjam')
                            ->where('user_id', Auth::id())
                            ->count(),
            default   => Peminjaman::where('status', 'dipinjam')->count(),
        };

        // -----------------------------------------------------------------
        // DASHBOARD ANGGOTA
        // -----------------------------------------------------------------
        if ($role === 'anggota') {
            return view('dashboard.anggota', compact(
                'jumlahBuku',
                'jumlahKategori',
                'jumlahAnggota',
                'jumlahPinjaman'
            ));
        }

        // -----------------------------------------------------------------
        // DASHBOARD PETUGAS
        // -----------------------------------------------------------------
        if ($role === 'petugas') {
            // Peminjaman yang menunggu konfirmasi petugas
            $menunggu = Peminjaman::with('user', 'buku')
                ->where('status', 'menunggu')
                ->latest()
                ->limit(5)
                ->get();

            // Aktivitas peminjaman terbaru
            $aktivitas = Peminjaman::with('user', 'buku')
                ->latest('updated_at')
                ->limit(5)
                ->get();

            // Data statistik status untuk diagram donat
            $statusData = Peminjaman::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            return view('dashboard.petugas', compact(
                'jumlahBuku',
                'jumlahKategori',
                'jumlahAnggota',
                'jumlahPinjaman',
                'menunggu',
                'aktivitas',
                'statusData'
            ));
        }

        // -----------------------------------------------------------------
        // DASHBOARD KEPALA
        // -----------------------------------------------------------------
        if ($role === 'kepala') {
            $jumlahPetugas = User::where('role', 'petugas')->count();
            $jumlahAnggota = User::where('role', 'anggota')->count();

            $statusData = Peminjaman::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            return view('dashboard.kepala', compact(
                'jumlahBuku',
                'jumlahKategori',
                'jumlahPinjaman',
                'jumlahPetugas',
                'jumlahAnggota',
                'statusData'
            ));
        }
    }
}
