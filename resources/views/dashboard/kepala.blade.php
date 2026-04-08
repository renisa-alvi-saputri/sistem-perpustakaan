@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <h2 class="text-xl sm:text-2xl font-semibold mb-6">
        Hai, {{ auth()->user()->name }} 👋
    </h2>

    <!-- Card Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

        <!-- JUMLAH BUKU -->
        <div class="w-full min-w-0 bg-gradient-to-r from-indigo-500 to-indigo-400 text-white p-5 md:p-6 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
            <div>
                <h3 class="text-xs sm:text-sm font-semibold">JUMLAH BUKU</h3>
                <p class="text-3xl sm:text-4xl md:text-5xl font-bold mt-2">
                    {{ $jumlahBuku ?? 0 }}
                </p>
            </div>
            <i class="fa-solid fa-book text-4xl sm:text-5xl md:text-6xl opacity-30"></i>
        </div>

        <!-- PINJAMAN SELESAI -->
        <div class="w-full min-w-0 bg-gradient-to-r from-green-500 to-green-400 text-white p-5 md:p-6 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
            <div>
                <h3 class="text-xs sm:text-sm font-semibold">PINJAMAN SELESAI</h3>
                <p class="text-3xl sm:text-4xl md:text-5xl font-bold mt-2">
                    {{ $jumlahPinjaman ?? 0 }}
                </p>
            </div>
            <i class="fa-solid fa-user-pen text-6xl opacity-30"></i>
        </div>

    </div>

</div>
@endsection
