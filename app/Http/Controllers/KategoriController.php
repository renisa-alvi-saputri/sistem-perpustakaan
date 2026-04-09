<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    // TAMPIL DATA
    public function index()
    {
        $data = \App\Models\Kategori::when(request('search'), function ($query) {
            $query->where('nama_kategori', 'like', '%' . request('search') . '%');
        })->get();  
        $role = Auth::user()->role;

        return view('kategori.' . $role, compact('data'));
    }

    // SIMPAN DATA
   public function store(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|unique:kategoris,nama_kategori'
    ], [
        'nama_kategori.unique' => 'Kategori ini sudah ada!'
    ]);

    Kategori::create([
        'nama_kategori' => $request->nama_kategori
    ]);

    return back()->with('success', 'Kategori berhasil ditambahkan');
}

    // HAPUS DATA
    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }

    // EDIT DATA
    public function edit($id)
    {
        $data = Kategori::findOrFail($id);
        return view('kategori.edit', compact('data'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
{
    $request->validate([
        'nama_kategori' => 'required|unique:kategoris,nama_kategori,' . $id
    ], [
        'nama_kategori.unique' => 'Kategori ini sudah ada!'
    ]);

    $kategori = Kategori::findOrFail($id);
    $kategori->update([
        'nama_kategori' => $request->nama_kategori
    ]);

    return redirect('/kategori')->with('success', 'Kategori berhasil diupdate');
}
}
