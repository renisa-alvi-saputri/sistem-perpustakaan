<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'anggota');

        if ($request->filled('search')) {
            $search = addcslashes($request->search, '%_');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $anggota = $query->paginate(20)->withQueryString();

        $user = auth()->user();

        if ($user && $user->role == 'kepala') {
            return view('anggota.kepala', compact('anggota'));
        }

        return view('anggota.petugas', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email|unique:users,email',
            'jenis_kelamin' => 'required',
            'password'      => 'required|min:6|confirmed',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah terdaftar.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'photo.image'            => 'File harus berupa gambar.',
            'photo.max'              => 'Ukuran foto maksimal 2MB.',
        ]);

        // ✅ Upload foto jika ada
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->store('photos', 'public');
        }

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password'      => bcrypt($request->password),
            'role'          => 'anggota',
            'photo'         => $photoName,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email|unique:users,email,' . $id,
            'jenis_kelamin' => 'required',
            'password'      => 'nullable|min:6',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah digunakan akun lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'password.min'           => 'Password baru minimal 6 karakter.',
            'photo.image'            => 'File harus berupa gambar.',
            'photo.max'              => 'Ukuran foto maksimal 2MB.',
        ]);

        $anggota = User::findOrFail($id);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // ✅ Upload foto baru, hapus foto lama jika ada
        if ($request->hasFile('photo')) {
            if ($anggota->photo) {
                Storage::disk('public')->delete($anggota->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $anggota->update($data);

        return back()->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = User::findOrFail($id);

        // ✅ Hapus foto saat anggota dihapus
        if ($anggota->photo) {
            Storage::disk('public')->delete($anggota->photo);
        }

        $anggota->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}
