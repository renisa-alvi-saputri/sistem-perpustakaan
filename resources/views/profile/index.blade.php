@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="flex justify-center mt-8">

        <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-4xl">

            <h2 class="text-xl font-semibold mb-8 text-gray-700">Profile</h2>

            <div class="flex flex-col md:flex-row gap-8 items-start">

                <!-- FOTO -->
                <div class="md:w-1/3 flex flex-col items-center pt-10">
                    @if (auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                            class="w-40 h-40 object-cover rounded-full border-4 border-gray-100 shadow-md">
                    @else
                        <div
                            class="w-40 h-40 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 text-sm border">
                            No Image
                        </div>
                    @endif
                </div>

                <!-- DATA -->
                <div class="md:w-2/3">

                    <!-- Nama -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Nama Lengkap</label>
                        <div class="w-full h-11 px-4 rounded-lg bg-gray-50 text-sm flex items-center border">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Jenis Kelamin</label>
                        <div class="w-full h-11 px-4 rounded-lg bg-gray-50 text-sm flex items-center border">
                            {{ auth()->user()->jenis_kelamin }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1 text-gray-600">Email</label>
                        <div class="w-full h-11 px-4 rounded-lg bg-gray-50 text-sm flex items-center border">
                            {{ auth()->user()->email }}
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-3">
                        <a href="/dashboard"
                            class="px-4 py-1.5 text-sm rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
                            Kembali
                        </a>

                        <a href="{{ route('profile.edit') }}"
                            class="px-4 py-1.5 text-sm rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition shadow">
                            Edit Profile
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
