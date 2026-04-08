<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AnggotaController extends Controller
{

    public function index(Request $request)
    {
        $query = User::where('role', 'anggota');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $anggota = $query->get();

        return view('anggota.petugas', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'jenis_kelamin' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => bcrypt('123456'),
            'role' => 'anggota'
        ]);

        return back()->with('success', 'Anggota berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'jenis_kelamin' => 'required',
        ]);

        $anggota = User::findOrFail($id);

        $anggota->update([
            'name' => $request->name,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return back()->with('success', 'Anggota berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'Anggota berhasil dihapus');
    }
}
