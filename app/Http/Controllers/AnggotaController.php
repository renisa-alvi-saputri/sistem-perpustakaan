<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AnggotaController extends Controller
{
    // Otorisasi ditangani di routes/web.php — lihat komentar di bawah file ini

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

        return view('anggota.petugas', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email|unique:users,email',
            'jenis_kelamin' => 'required',
            'password'      => 'required|min:6|confirmed',
        ], [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah terdaftar.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password'      => bcrypt($request->password),
            'role'          => 'anggota',
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
        ], [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah digunakan akun lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'password.min'           => 'Password baru minimal 6 karakter.',
        ]);

        $anggota = User::findOrFail($id);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $anggota->update($data);

        return back()->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}

/*
|--------------------------------------------------------------------------
| Tambahkan ini di routes/web.php
|--------------------------------------------------------------------------
|
| Route::middleware(['auth', function ($request, $next) {
|     abort_unless(auth()->user()->role === 'petugas', 403, 'Akses ditolak.');
|     return $next($request);
| }])->group(function () {
|     Route::resource('anggota', AnggotaController::class);
| });
|
*/
