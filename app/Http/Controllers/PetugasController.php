<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->filled('search')) {
            $search = addcslashes($request->search, '%_');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('jenis_kelamin', 'like', '%' . $search . '%');
            });
        }

        $petugas = $query->get();

        return view('petugas.kepala', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:users,name',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.unique'  => 'Nama petugas sudah digunakan!',
            'email.unique' => 'Email sudah digunakan!',
            'photo.image'  => 'File harus berupa gambar.',
            'photo.max'    => 'Ukuran foto maksimal 2MB.',
        ]);

        // ✅ Upload foto jika ada
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->store('photos', 'public');
        }

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'petugas',
            'jenis_kelamin' => $request->jenis_kelamin,
            'photo'         => $photoName,
        ]);

        return redirect()->route('petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255|unique:users,name,' . $id,
            'email'         => 'required|email|unique:users,email,' . $id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.unique'  => 'Nama petugas sudah digunakan!',
            'email.unique' => 'Email sudah digunakan!',
            'photo.image'  => 'File harus berupa gambar.',
            'photo.max'    => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // ✅ Upload foto baru, hapus foto lama
        if ($request->hasFile('photo')) {
            if ($petugas->photo) {
                Storage::disk('public')->delete($petugas->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $petugas->update($data);

        return redirect()->route('petugas.index')
            ->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        $petugas = User::findOrFail($id);

        // ✅ Hapus foto saat petugas dihapus
        if ($petugas->photo) {
            Storage::disk('public')->delete($petugas->photo);
        }

        $petugas->delete();

        return redirect()->route('petugas.index')
            ->with('success', 'Petugas berhasil dihapus!');
    }
}
