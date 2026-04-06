@extends('layouts.app')

@section('title', 'Buku')

@section('content')

    <!-- SEARCH -->
    <form method="GET" action="{{ route('buku.index') }}" class="mb-6">
        <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}"
            onkeyup="this.form.submit()"
            class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
    </form>

    <!-- LIST BUKU -->
    <div class="grid grid-cols-3 gap-6">

        @foreach ($buku as $b)
            <div class="bg-white rounded-xl shadow-md flex overflow-hidden">

                <!-- COVER -->
                <img src="{{ $b->cover ? asset('cover/' . $b->cover) : 'https://picsum.photos/120/160' }}"
                    class="w-32 h-44 object-cover">

                <!-- DETAIL -->
                <div class="p-4 pt-6 flex flex-col flex-1">

                    <div>
                        <h3 class="font-semibold text-lg text-[#5C7F9C]">
                            {{ $b->judul }}
                        </h3>

                        <p class="text-sm text-gray-600">
                            Kategori :
                            {{ $b->kategori->nama_kategori ?? '-' }}
                        </p>

                        <p class="text-sm text-gray-600">
                            Stok : {{ $b->stok }}
                        </p>
                    </div>

                    <!-- BUTTON -->
                    <div class="flex gap-2 mt-3 text-sm items-center">

                        <!-- DETAIL -->
                        <button
                            onclick="showDetail('{{ $b->id }}', '{{ $b->judul }}', '{{ $b->penulis }}', '{{ $b->stok }}', '{{ $b->kategori->nama_kategori ?? '-' }}', '{{ $b->cover }}', '{{ $b->tahun_terbit }}')"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors duration-200 gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Detail
                        </button>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <!-- ================= MODAL DETAIL ================= -->
    <div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">

        <div class="bg-white p-6 rounded-2xl w-80 text-center shadow-lg relative">

            <!-- COVER -->
            <img id="detailCover" class="w-32 h-44 object-cover mx-auto mb-4 rounded shadow">

            <!-- JUDUL -->
            <h3 id="detailJudul" class="font-bold text-lg text-[#5C7F9C]"></h3>

            <!-- INFO -->
            <div class="text-sm text-gray-600 space-y-1 mt-2">
                <p id="detailId"></p>
                <p id="detailPenulis"></p>
                <p id="detailTahunTerbit"></p>
                <p id="detailKategori"></p>
                <p id="detailStok"></p>
            </div>

            <!-- BUTTON -->
            <button onclick="closeDetail()" class="mt-4 bg-gray-400 text-white px-4 py-1 rounded-lg hover:bg-gray-500">
                Tutup
            </button>

        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        function showDetail(id, judul, penulis, stok, kategori, cover, tahun) {
            document.getElementById('modalDetail').classList.remove('hidden');

            document.getElementById('detailId').innerText = "ID Buku: " + id;
            document.getElementById('detailJudul').innerText = judul;
            document.getElementById('detailPenulis').innerText = "Penulis: " + penulis;
            document.getElementById('detailTahunTerbit').innerText = "Tahun Terbit: " + tahun;
            document.getElementById('detailKategori').innerText = "Kategori: " + kategori;
            document.getElementById('detailStok').innerText = "Stok: " + stok;

            if (cover) {
                document.getElementById('detailCover').src = "/cover/" + cover;
            } else {
                document.getElementById('detailCover').src = "https://picsum.photos/120/160";
            }
        }

        function closeDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }
    </script>

@endsection
