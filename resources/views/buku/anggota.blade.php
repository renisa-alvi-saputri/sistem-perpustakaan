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
                            class="bg-blue-100 text-blue-600 px-2 py-0.5 text-xs rounded-full">
                            detail
                        </button>

                        <!-- PINJAM -->
                        <button onclick="openPinjam({{ $b->id }}, '{{ $b->judul }}')"
                            class="bg-green-100 text-green-600 px-2 py-0.5 text-xs rounded-full">
                            pinjam
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

    <!-- ================= MODAL PINJAM ================= -->
    <div id="modalPinjam" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white w-96 p-6 rounded-2xl shadow-lg">

            <!-- Judul -->
            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-2 text-center">
                Pinjam Buku
            </h2>

            <!-- Nama Buku -->
            <p id="namaBuku" class="text-sm text-gray-500 mb-5 text-center"></p>

            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf

                <!-- Hidden input -->
                <input type="hidden" name="buku_id" id="pinjamBukuId">

                <!-- Button -->
                <div class="flex justify-center gap-3 mt-4">

                    <button type="button" onclick="closePinjam()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                        Pinjam
                    </button>
                </div>
            </form>

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

        function openPinjam(bukuId, bukuNama) {
            document.getElementById('modalPinjam').classList.remove('hidden');
            document.getElementById('pinjamBukuId').value = bukuId;
            document.getElementById('namaBuku').innerText = bukuNama;
        }

        function closePinjam() {
            document.getElementById('modalPinjam').classList.add('hidden');
        }
    </script>

@endsection
