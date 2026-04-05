<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    // ================== INDEX ==================
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        $buku = $query->get();
        $kategori = Kategori::all();

        if (Auth::user()->role == 'petugas') {
            return view('buku.petugas', compact('buku', 'kategori'));
        }

        if (Auth::user()->role == 'kepala') {
            return view('buku.kepala', compact('buku'));
        }

        return view('buku.anggota', compact('buku'));
    }

    // ================== STORE ==================
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|unique:bukus,judul', // unik
            'penulis' => 'required',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'tahun_terbit' => 'required|integer',
        ], [
            'judul.unique' => 'Judul buku sudah ada, silakan ganti', // pesan duplikat
        ]);

        $namaFile = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('cover'), $namaFile);
        }

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
            'cover' => $namaFile,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    // ================== UPDATE ==================
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required|unique:bukus,judul,' . $buku->id,
            'penulis' => 'required',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'tahun_terbit' => 'required|integer',
        ], [
            'judul.unique' => 'Judul buku sudah ada, silakan ganti',
        ]);

        $namaFile = $buku->cover;
        if ($request->hasFile('cover')) {
            if ($buku->cover && file_exists(public_path('cover/' . $buku->cover))) {
                unlink(public_path('cover/' . $buku->cover));
            }
            $file = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('cover'), $namaFile);
        }

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
            'cover' => $namaFile,
            'tahun_terbit' => $request->tahun_terbit,
        ]);

        return back()->with('success', 'Buku berhasil diupdate');
    }

    // ================== DELETE ==================
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover && file_exists(public_path('cover/' . $buku->cover))) {
            unlink(public_path('cover/' . $buku->cover));
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }

    // ================== DETAIL ==================
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return response()->json($buku);
    }
}
