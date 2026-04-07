@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <h2 class="text-xl sm:text-2xl font-semibold mb-6 text-gray-800">
            Hai, {{ auth()->user()->name }} 👋
        </h2>

        <!-- Card Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-8">

            <!-- JUMLAH BUKU -->
            <div
                class="relative w-full bg-gradient-to-r from-indigo-500 to-indigo-400 text-white p-5 md:p-6 rounded-xl flex flex-col justify-between shadow-lg hover:scale-105 transition-transform duration-300">
                <div>
                    <h3 class="text-xs sm:text-sm font-semibold uppercase tracking-wider">Jumlah Buku</h3>
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold mt-2">
                        {{ $jumlahBuku ?? 0 }}
                    </p>
                </div>
                <i class="fa-solid fa-book text-5xl absolute bottom-3 right-3 opacity-20"></i>
            </div>

            <!-- PINJAMAN SELESAI -->
            <div
                class="relative w-full bg-gradient-to-r from-green-500 to-green-400 text-white p-5 md:p-6 rounded-xl flex flex-col justify-between shadow-lg hover:scale-105 transition-transform duration-300">
                <div>
                    <h3 class="text-xs sm:text-sm font-semibold uppercase tracking-wider">Pinjaman Selesai</h3>
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold mt-2">
                        {{ $jumlahPinjaman ?? 0 }}
                    </p>
                </div>
                <i class="fa-solid fa-check-to-slot text-5xl absolute bottom-3 right-3 opacity-20"></i>
            </div>

            <!-- PETUGAS TERDAFTAR -->
            <div
                class="relative w-full bg-gradient-to-r from-purple-500 to-purple-400 text-white p-5 md:p-6 rounded-xl flex flex-col justify-between shadow-lg hover:scale-105 transition-transform duration-300">
                <div>
                    <h3 class="text-xs sm:text-sm font-semibold uppercase tracking-wider">Petugas Terdaftar</h3>
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold mt-2">
                        {{ $jumlahPetugas ?? 0 }}
                    </p>
                </div>
                <i class="fa-solid fa-user-tie text-5xl absolute bottom-3 right-3 opacity-20"></i>
            </div>

        </div>

    </div>
@endsection
