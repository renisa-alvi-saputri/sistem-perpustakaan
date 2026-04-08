<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // ================= INDEX + SEARCH =================
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('jenis_kelamin', 'like', '%' . $request->search . '%');
            });
        }

        $petugas = $query->get();

        return view('petugas.kepala', compact('petugas'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:users,name',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'jenis_kelamin' => 'required|in:L,P',
        ], [
            'name.unique'  => 'Nama petugas sudah digunakan!',
            'email.unique' => 'Email sudah digunakan!',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'petugas',
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan!');
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255|unique:users,name,' . $id,
            'email'         => 'required|email|unique:users,email,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
        ], [
            'name.unique'  => 'Nama petugas sudah digunakan!',
            'email.unique' => 'Email sudah digunakan!',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        // kalau isi password baru
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petugas->update($data);

        return redirect()->route('petugas.index')
            ->with('success', 'Data berhasil diupdate!');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $petugas = User::findOrFail($id);
        $petugas->delete();

        return redirect()->route('petugas.index')
            ->with('success', 'Petugas berhasil dihapus!');
    }
}
