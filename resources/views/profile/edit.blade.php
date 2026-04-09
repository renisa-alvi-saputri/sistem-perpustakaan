@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="flex justify-center mt-8">

    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-4xl">

        <h2 class="text-xl font-semibold mb-8 text-gray-700">Profile</h2>

        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-700 text-sm px-4 py-2 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex gap-8">

            <!-- FOTO -->
            <div class="w-1/3 flex flex-col items-center pt-1">
                @if (auth()->user()->photo)
                    <img src="{{ asset('foto_profile/' . auth()->user()->photo) }}"
                        class="w-40 h-40 object-cover rounded-full border-4 border-gray-100 shadow-md">
                @else
                    <div class="w-40 h-40 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 text-sm border">
                        No Image
                    </div>
                @endif
            </div>

            <!-- FORM -->
            <div class="w-2/3">

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Nama -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full h-10 px-3 border rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] focus:outline-none @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full h-10 px-3 border rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] focus:outline-none">
                            <option value="Laki-laki" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full h-10 px-3 border rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] focus:outline-none @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Foto -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Ganti Foto</label>
                        <input type="file" name="photo"
                            class="w-full text-sm border rounded-lg p-2 bg-gray-50">
                    </div>

                    <!-- Ganti Password -->
                    <div class="mb-5 border-t pt-5">
                        <p class="text-sm font-medium text-gray-600 mb-3">Ganti Password <span class="text-gray-400 font-normal">(opsional)</span></p>

                        <div class="mb-3">
                            <label class="block text-sm mb-1 text-gray-500">Password Lama</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password"
                                    placeholder="Masukkan password lama"
                                    class="w-full h-10 px-3 pr-10 border rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] focus:outline-none @error('current_password') border-red-400 @enderror">
                                <button type="button" onclick="togglePw('current_password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm mb-1 text-gray-500">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="new_password"
                                    placeholder="Minimal 6 karakter"
                                    class="w-full h-10 px-3 pr-10 border rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] focus:outline-none @error('password') border-red-400 @enderror">
                                <button type="button" onclick="togglePw('new_password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm mb-1 text-gray-500">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirm_password"
                                    placeholder="Ulangi password baru"
                                    class="w-full h-10 px-3 pr-10 border rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] focus:outline-none">
                                <button type="button" onclick="togglePw('confirm_password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('profile') }}"
                            class="px-4 py-1.5 text-sm rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
                            Kembali
                        </a>
                        <button
                            class="px-4 py-1.5 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600 transition shadow">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('svg').style.opacity = isHidden ? '0.4' : '1';
}
</script>

@endsection
