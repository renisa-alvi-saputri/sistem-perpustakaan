<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku dengan fitur pencarian.
     * Tampilan berbeda sesuai role: petugas, kepala, atau anggota.
     */
    public function index(Request $request)
{
    $query = Buku::with('kategori');

    if ($request->filled('search')) {
        $search = addcslashes($request->search, '%_');
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', '%' . $search . '%')
              ->orWhere('penulis', 'like', '%' . $search . '%');
        });
    }

    $buku     = $query->get();
    $kategori = Kategori::all();

    if (Auth::user()->role === 'petugas') {
        return view('buku.petugas', compact('buku', 'kategori'));
    }

    if (Auth::user()->role === 'kepala') {
        return view('buku.kepala', compact('buku'));
    }

    // Hitung buku aktif anggota untuk cek batas 3
    $jumlahAktif = \App\Models\Peminjaman::where('user_id', Auth::id())
        ->whereIn('status', ['menunggu', 'dipinjam'])
        ->count();

    return view('buku.anggota', compact('buku', 'jumlahAktif'));
}

    /**
     * Simpan buku baru beserta cover-nya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|unique:bukus,judul',
            'penulis'      => 'required',
            'stok'         => 'required|integer|min:0',
            'kategori_id'  => 'required|exists:kategoris,id',
            'tahun_terbit' => 'required|integer',
        ], [
            'judul.unique' => 'Judul buku sudah ada, silakan ganti.',
        ]);

        $namaFile = null;
        if ($request->hasFile('cover')) {
            $file     = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('cover'), $namaFile);
        }

        Buku::create([
            'judul'        => $request->judul,
            'penulis'      => $request->penulis,
            'stok'         => $request->stok,
            'kategori_id'  => $request->kategori_id,
            'cover'        => $namaFile,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Update data buku.
     * Jika ada cover baru, cover lama dihapus dari storage.
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul'        => 'required|unique:bukus,judul,' . $buku->id,
            'penulis'      => 'required',
            'stok'         => 'required|integer|min:0',
            'kategori_id'  => 'required|exists:kategoris,id',
            'tahun_terbit' => 'required|integer',
        ], [
            'judul.unique' => 'Judul buku sudah ada, silakan ganti.',
        ]);

        $namaFile = $buku->cover;
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover && file_exists(public_path('cover/' . $buku->cover))) {
                unlink(public_path('cover/' . $buku->cover));
            }

            $file     = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('cover'), $namaFile);
        }

        $buku->update([
            'judul'        => $request->judul,
            'penulis'      => $request->penulis,
            'stok'         => $request->stok,
            'kategori_id'  => $request->kategori_id,
            'cover'        => $namaFile,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return back()->with('success', 'Buku berhasil diupdate.');
    }

    /**
     * Hapus buku beserta cover-nya dari storage.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover && file_exists(public_path('cover/' . $buku->cover))) {
            unlink(public_path('cover/' . $buku->cover));
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * Mengembalikan detail buku dalam format JSON (untuk modal/ajax).
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return response()->json($buku);
    }
}
