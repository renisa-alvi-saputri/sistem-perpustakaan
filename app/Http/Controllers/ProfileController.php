<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'          => 'required',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'jenis_kelamin' => 'required',
            'photo'         => 'nullable|image|max:2048',
        ];

        $messages = [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah digunakan akun lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'photo.image'            => 'File harus berupa gambar.',
            'photo.max'              => 'Ukuran foto maksimal 2MB.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin ganti password.',
            'password.min'           => 'Password baru minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ];

        // Validasi password hanya jika password baru diisi
        if ($request->filled('password')) {
            $rules['current_password'] = 'required_with:password';
            $rules['password']         = 'min:6|confirmed';
        }

        $request->validate($rules, $messages);

        // Cek password lama kalau user mau ganti password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                    ->withInput();
            }
        }

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        // Update password kalau diisi
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Upload foto kalau ada
        if ($request->hasFile('photo')) {
            $filename = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('foto_profile'), $filename);
            $data['photo'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
