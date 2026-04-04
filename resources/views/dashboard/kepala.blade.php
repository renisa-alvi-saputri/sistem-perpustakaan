@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-6">

    <h2 class="text-2xl font-semibold mb-6">
        Hai, {{ auth()->user()->name }} 👋
    </h2>

    <!-- Card Statistik -->
    <div class="grid grid-cols-2 gap-5 mb-8">

        <!-- Buku -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-400 text-white p-5 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
            <div>
                <h3 class="text-sm font-semibold">JUMLAH BUKU</h3>
                <p class="text-5xl font-bold mt-2">
                    {{ $jumlahBuku ?? 0 }}
                </p>
            </div>
            <i class="fa-solid fa-book text-6xl opacity-30"></i>
        </div>

        <!-- Pinjaman -->
        <div class="bg-gradient-to-r from-green-500 to-green-400 text-white p-5 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
            <div>
                <h3 class="text-sm font-semibold">PINJAMAN SAAT INI</h3>
                <p class="text-5xl font-bold mt-2">
                    {{ $jumlahPinjaman ?? 0 }}
                </p>
            </div>
            <i class="fa-solid fa-user-pen text-6xl opacity-30"></i>
        </div>

    </div>

</div>
@endsection
