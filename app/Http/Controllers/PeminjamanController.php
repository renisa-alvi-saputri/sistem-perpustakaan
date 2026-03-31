<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $peminjaman = Peminjaman::with('user', 'buku')->get();
        $anggota = User::where('role', 'anggota')->get();
        $buku = Buku::where('stok', '>', 0)->get();

        // 👮 PETUGAS
        if (Auth::user()->role == 'petugas') {
            return view('peminjaman.petugas', compact('peminjaman', 'anggota', 'buku'));
        }

        // 👤 ANGGOTA
        return view('peminjaman.anggota', compact('peminjaman'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok habis!');
        }

        // 👮 PETUGAS (manual pilih user)
        if (Auth::user()->role == 'petugas') {
            Peminjaman::create([
                'user_id' => $request->user_id,
                'buku_id' => $buku->id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'dipinjam'
            ]);
        }
        // 👤 ANGGOTA (otomatis user login)
        else {
            Peminjaman::create([
                'user_id' => Auth::id(),
                'buku_id' => $buku->id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'dipinjam'
            ]);
        }

        $buku->decrement('stok');

        return back()->with('success', 'Berhasil pinjam!');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        Peminjaman::findOrFail($id)->delete();
        return back();
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    $peminjaman->update([
        'user_id' => $request->user_id,
        'buku_id' => $request->buku_id,
        'tgl_pinjam' => $request->tgl_pinjam,
        'tgl_kembali' => $request->tgl_kembali,
    ]);

    return back()->with('success', 'Data berhasil diupdate');
}
}
