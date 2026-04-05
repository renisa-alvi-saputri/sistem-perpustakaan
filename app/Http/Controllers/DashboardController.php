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

        // ================= DATA =================
        $jumlahBuku = Buku::count();
        $jumlahKategori = Kategori::count();
        $jumlahAnggota = User::where('role', 'anggota')->count();

        // Default: dipinjam
        $jumlahPinjaman = Peminjaman::where('status', 'dipinjam')->count();

        // 👑 Kepala: selesai
        if ($role == 'kepala') {
            $jumlahPinjaman = Peminjaman::where('status', 'selesai')->count();
        }

        // ================= VIEW =================
        if ($role == 'anggota') {
            return view('dashboard.anggota', compact(
                'jumlahBuku',
                'jumlahKategori',
                'jumlahAnggota',
                'jumlahPinjaman'
            ));
        } elseif ($role == 'petugas') {
            return view('dashboard.petugas', compact(
                'jumlahBuku',
                'jumlahKategori',
                'jumlahAnggota',
                'jumlahPinjaman'
            ));
        } elseif ($role == 'kepala') {
            return view('dashboard.kepala', compact(
                'jumlahBuku',
                'jumlahKategori',
                'jumlahAnggota',
                'jumlahPinjaman'
            ));
        }
    }
}
