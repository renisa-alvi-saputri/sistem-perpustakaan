<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // asumsi petugas disimpan di tabel users
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // ================= INDEX =================
    // Menampilkan daftar petugas
    public function index()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('petugas.kepala', compact('petugas'));
    }

    // ================= CREATE =================
    // Menampilkan form tambah petugas
    public function create()
    {
        return view('petugas.kepala'); // kita pakai halaman sama untuk form
    }

    // ================= STORE =================
    // Menyimpan petugas baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Simpan petugas baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas', // otomatis jadi petugas
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }
}
