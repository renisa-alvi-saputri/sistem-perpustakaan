@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-2xl font-semibold mb-6">
            Hai, {{ auth()->user()->name }} 👋
        </h2>

        <!-- Card Statistik -->
        <div class="grid grid-cols-2 gap-5 mb-8">

            <div
                class="bg-gradient-to-r from-indigo-500 to-indigo-400 text-white p-5 rounded-xl flex justify-between items-center shadow">
                <div>
                    <h3 class="text-sm font-semibold">JUMLAH BUKU</h3>
                    <p class="text-5xl font-bold mt-2">{{ $jumlahBuku ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-book text-6xl opacity-30"></i>
            </div>

            <div
                class="bg-gradient-to-r from-green-500 to-green-400 text-white p-5 rounded-xl flex justify-between items-center shadow">
                <div>
                    <h3 class="text-sm font-semibold">PINJAMAN SAAT INI</h3>
                    <p class="text-5xl font-bold mt-2">{{ $jumlahPinjaman ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-user-pen text-6xl opacity-30"></i>
            </div>

        </div>

        <!-- Informasi Aturan Peminjaman -->
        <div class="bg-gray-100 rounded-xl p-6">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-info text-[#5C7F9C]"></i>
                Informasi Aturan Peminjaman
            </h3>
            <ol class="space-y-2 text-sm text-gray-600 list-decimal list-inside">
                <li>Waktu peminjaman maksimal 1 minggu.</li>
                <li>Setiap anggota hanya dapat meminjam maksimal 3 buku.</li>
                <li>Jika pengembalian buku lebih dari waktu yang ditentukan akan dikenakan denda setiap buku Rp.2.000/hari.
                </li>
                <li>Jika telah meminjam buku, silahkan ke tempat petugas untuk melakukan konfirmasi.</li>
                <li>Jika terlambat mengembalikan buku dan mendapat denda, maka wajib langsung membayar pada petugas saat
                    mengembalikan buku.</li>
            </ol>
        </div>

    </div>
@endsection
