@extends('layouts.app')

@section('title', 'Buku')

@section('content')

    {{-- LOADING + TOAST NOTIFIKASI --}}
    @if (session('success'))
        {{-- LOADING SPINNER --}}
        <div id="loading"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
            <div class="bg-white rounded-2xl p-6 flex flex-col items-center gap-3 shadow-lg">
                <svg class="w-10 h-10 text-[#5C7F9C] animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm text-[#5C7F9C] font-medium">Memproses...</p>
            </div>
        </div>

        {{-- TOAST --}}
        <div id="toast"
            class="hidden fixed top-24 left-1/2 -translate-x-1/2 z-50 bg-[#5C7F9C] text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transition-all duration-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('loading').remove();
                const toast = document.getElementById('toast');
                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }, 1500);
        </script>
    @endif

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
                    <div class="flex gap-2 mt-3">

                        <!-- DETAIL -->
                        <button
                            onclick="showDetail('{{ $b->id }}', '{{ $b->judul }}', '{{ $b->penulis }}', '{{ $b->stok }}', '{{ $b->kategori->nama_kategori ?? '-' }}', '{{ $b->cover }}', '{{ $b->tahun_terbit }}')"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Detail
                        </button>

                        <!-- PINJAM -->
                        @if ($b->stok > 0)
                            <button onclick="openPinjam({{ $b->id }}, '{{ $b->judul }}', {{ $b->stok }})"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors duration-200 gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Pinjam
                            </button>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 gap-1">
                                Stok Habis
                            </span>
                        @endif

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <!-- MODAL PINJAM -->
    <div id="modalPinjam" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white w-96 p-6 rounded-2xl shadow-lg">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-2 text-center">
                Pinjam Buku
            </h2>

            <p id="namaBuku" class="text-sm text-gray-500 mb-5 text-center"></p>

            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf
                <input type="hidden" name="buku_id" id="pinjamBukuId">

                <div class="flex justify-center gap-3 mt-4">
                    <button type="button" onclick="closePinjam()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg">
                        Pinjam
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white w-96 p-6 rounded-2xl shadow-lg flex flex-col items-center">

            <img id="detailCover" src="" alt="Cover Buku" class="w-32 h-44 object-cover mb-3 rounded-lg shadow">

            <h2 id="detailJudul" class="text-lg font-semibold text-[#5C7F9C] mb-2 text-center"></h2>
            <p id="detailPenulis" class="text-sm text-gray-500 mb-1 text-center"></p>
            <p id="detailKategori" class="text-sm text-gray-500 mb-1 text-center"></p>
            <p id="detailStok" class="text-sm text-gray-500 mb-1 text-center"></p>
            <p id="detailTahun" class="text-sm text-gray-500 mb-4 text-center"></p>

            <div class="flex justify-center">
                <button type="button" onclick="closeDetail()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function openPinjam(bukuId, bukuNama, stok) {
            if (stok <= 0) {
                alert("Stok habis, buku tidak bisa dipinjam!");
                return;
            }
            document.getElementById('modalPinjam').classList.remove('hidden');
            document.getElementById('pinjamBukuId').value = bukuId;
            document.getElementById('namaBuku').innerText = bukuNama;
        }

        function closePinjam() {
            document.getElementById('modalPinjam').classList.add('hidden');
        }

        function showDetail(id, judul, penulis, stok, kategori, cover, tahun) {
            document.getElementById('modalDetail').classList.remove('hidden');
            document.getElementById('detailJudul').innerText = judul;
            document.getElementById('detailPenulis').innerText = 'Penulis: ' + penulis;
            document.getElementById('detailKategori').innerText = 'Kategori: ' + kategori;
            document.getElementById('detailStok').innerText = 'Stok: ' + stok;
            document.getElementById('detailTahun').innerText = 'Tahun Terbit: ' + tahun;
            document.getElementById('detailCover').src = cover ?
                '{{ asset('cover') }}/' + cover :
                'https://picsum.photos/120/160';
        }

        function closeDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }
    </script>

@endsection
