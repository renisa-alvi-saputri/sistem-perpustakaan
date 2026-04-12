<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori dengan fitur pencarian.
     * Tampilan disesuaikan berdasarkan role user yang login.
     */
    public function index()
    {
        $data = Kategori::when(request('search'), function ($query) {
                $search = addcslashes(request('search'), '%_');
                $query->where('nama_kategori', 'like', '%' . $search . '%');
            })->get();

        $role = Auth::user()->role;

        return view('kategori.' . $role, compact('data'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori ini sudah ada!',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit($id)
    {
        $data = Kategori::findOrFail($id);

        return view('kategori.edit', compact('data'));
    }

    /**
     * Update data kategori.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori,' . $id,
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori ini sudah ada!',
        ]);

        Kategori::findOrFail($id)->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect('/kategori')->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Hapus kategori.
     */
    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();

        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
