@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="flex justify-center mt-6">

    <div class="bg-white p-6 rounded-xl shadow w-full max-w-4xl">

        <h2 class="text-xl font-semibold mb-6">Profile</h2>

        <div class="flex flex-col md:flex-row gap-6">

            <!-- FOTO -->
            <div class="md:w-1/3 flex flex-col items-center mt-5">
                @if (auth()->user()->photo)
                    <img src="{{ asset('foto_profile/' . auth()->user()->photo) }}"
                        class="w-40 h-40 object-cover rounded-full border shadow">
                @else
                    <div class="w-40 h-40 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 text-sm">
                        No Image
                    </div>
                @endif
            </div>

            <!-- DATA -->
            <div class="md:w-2/3">

                <!-- Nama -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                    <div class="w-full h-10 px-3 border rounded bg-gray-100 text-sm flex items-center">
                        {{ auth()->user()->name }}
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                    <div class="w-full h-10 px-3 border rounded bg-gray-100 text-sm flex items-center">
                        {{ auth()->user()->jenis_kelamin }}
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <div class="w-full h-10 px-3 border rounded bg-gray-100 text-sm flex items-center">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-2">
                    <a href="/dashboard"
                        class="bg-gray-500 text-white px-3 py-1.5 text-sm rounded hover:bg-gray-400">
                        Kembali
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="bg-blue-500 text-white px-3 py-1.5 text-sm rounded hover:bg-blue-400">
                        Edit Profile
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
