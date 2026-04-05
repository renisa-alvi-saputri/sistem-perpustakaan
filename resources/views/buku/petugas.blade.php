@extends('layouts.app')

@section('title', 'Buku')

@section('content')
    <div class="flex gap-4 mb-6 items-center">

        <!-- BUTTON -->
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow">
            + Buku
        </button>

        <!-- SEARCH -->
        <form method="GET" action="{{ route('buku.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>

    </div>

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
                            <span class="text-gray-600">
                                {{ $b->kategori->nama_kategori ?? '-' }}
                            </span>
                        </p>

                        <p class="text-sm text-gray-600">Stok : {{ $b->stok }}</p>
                    </div>

                    <!-- BUTTON -->
                    <div class="flex gap-2 mt-3 text-sm items-center">

                        <!-- DETAIL -->
                        <button
                            onclick="showDetail('{{ $b->id }}', '{{ $b->judul }}', '{{ $b->penulis }}', '{{ $b->stok }}', '{{ $b->kategori->nama_kategori ?? '-' }}', '{{ $b->cover }}', '{{ $b->tahun_terbit }}')"
                            class="bg-blue-100 text-blue-600 px-2 py-0.5 text-xs rounded-full">
                            detail
                        </button>

                        <!-- EDIT -->
                        <button
                            onclick="editBuku({{ $b->id }}, '{{ $b->judul }}', '{{ $b->penulis }}', {{ $b->stok }}, {{ $b->kategori_id }}, {{ $b->tahun_terbit ?? 0 }})"
                            class="bg-yellow-200 text-yellow-800 px-2 py-0.5 text-xs rounded-full">
                            edit
                        </button>

                        <!-- DELETE -->
                        <button onclick="openDelete({{ $b->id }})"
                            class="bg-red-100 text-red-600 px-2 py-0.5 text-xs rounded-full">
                            hapus
                        </button>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Tambah Buku</h2>

            <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data" class="space-y-2">
                @csrf

                <!-- Judul -->
                <label for="judul" class="text-gray-500 text-sm block mb-1">Judul Buku</label>
                <input type="text" name="judul" placeholder="Masukkan judul buku" value="{{ old('judul') }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Penulis -->
                <label for="penulis" class="text-gray-500 text-sm block mb-1">Penulis</label>
                <input type="text" name="penulis" id="penulis" placeholder="Masukkan nama penulis"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- Tahun Terbit -->
                <label for="tahun_terbit" class="text-gray-500 text-sm block mb-1">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" placeholder="Masukkan tahun terbit"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- Stok -->
                <label for="stok" class="text-gray-500 text-sm block mb-1">Stok</label>
                <input type="number" name="stok" id="stok" placeholder="Masukkan jumlah stok"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- Kategori -->
                <label for="kategori_id" class="text-gray-500 text-sm block mb-1">Kategori</label>
                <select name="kategori_id" id="kategori_id"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <!-- Cover -->
                <label for="cover" class="text-gray-500 text-sm block mb-1">Cover Buku</label>
                <input type="file" name="cover" id="cover" class="w-full text-sm">

                <!-- Tombol -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-1 bg-green-500 text-white rounded-lg hover:bg-green-400">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Edit Buku</h2>

            <form method="POST" id="formEdit" enctype="multipart/form-data" class="space-y-2">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <label for="editJudul" class="text-gray-500 text-sm block mb-1">Judul Buku</label>
                <input type="text" id="editJudul" name="judul" placeholder="Masukkan judul buku"
                    value="{{ old('judul', session('edit.judul') ?? '') }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Penulis -->
                <label for="editPenulis" class="text-gray-500 text-sm block mb-1">Penulis</label>
                <input type="text" id="editPenulis" name="penulis" placeholder="Masukkan nama penulis"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- Tahun Terbit -->
                <label for="editTahunTerbit" class="text-gray-500 text-sm block mb-1">Tahun Terbit</label>
                <input type="number" id="editTahunTerbit" name="tahun_terbit" placeholder="Masukkan tahun terbit"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- Stok -->
                <label for="editStok" class="text-gray-500 text-sm block mb-1">Stok</label>
                <input type="number" id="editStok" name="stok" placeholder="Masukkan jumlah stok"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- Kategori -->
                <label for="editKategori" class="text-gray-500 text-sm block mb-1">Kategori</label>
                <select id="editKategori" name="kategori_id"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <!-- Cover -->
                <label for="cover" class="text-gray-500 text-sm block mb-1">Cover Buku</label>
                <input type="file" name="cover" id="cover" class="w-full text-sm">

                <!-- Tombol -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEdit()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= MODAL DETAIL ================= -->
    <div id="modalDetail" onclick="closeDetail()" class="hidden fixed inset-0 bg-black bg-opacity-40 z-[9999]">

        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
        bg-white p-6 rounded-2xl w-80 text-center shadow-lg">

            <img id="detailCover" class="w-32 h-44 object-cover mx-auto mb-4 rounded shadow">

            <h3 id="detailJudul" class="font-bold text-lg text-[#5C7F9C]"></h3>

            <div class="text-sm text-gray-600 space-y-1 mt-2">
                <p id="detailId"></p>
                <p id="detailPenulis"></p>
                <p id="detailTahunTerbit"></p>
                <p id="detailKategori"></p>
                <p id="detailStok"></p>
            </div>

            <button onclick="closeDetail()" class="mt-4 bg-gray-400 text-white px-4 py-1 rounded-lg hover:bg-gray-500">
                Tutup
            </button>

        </div>
    </div>

    <!-- ================= MODAL DELETE ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-xl w-80 shadow text-center">

            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">
                Hapus Buku
            </h3>

            <p class="text-sm text-gray-600 mb-6">
                Yakin ingin menghapus buku ini?
            </p>

            <form id="formDelete" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center gap-3">

                    <button type="button" onclick="closeDelete()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">
                        Batal
                    </button>

                    <button class="px-4 py-1 bg-red-500 text-white rounded-lg hover:bg-red-400">
                        Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function editBuku(id, judul, penulis, stok, kategori_id, tahun_terbit) {
            document.getElementById('modalEdit').classList.remove('hidden');

            document.getElementById('editJudul').value = judul;
            document.getElementById('editPenulis').value = penulis;
            document.getElementById('editStok').value = stok;
            document.getElementById('editTahunTerbit').value = tahun_terbit;
            document.getElementById('editKategori').value = kategori_id;

            document.getElementById('formEdit').action = "/buku/" + id;
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

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

        function openDelete(id) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('formDelete').action = "/buku/" + id;
        }

        function closeDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }

        //<!--Jika error judul tambah buku-- >
        @if ($errors->has('judul') && !session('edit'))
            openModal();
        @endif

        //<!--Jika error judul edit buku-- >
        @if (session('edit'))
            let edit = @json(session('edit'));
            editBuku(edit.id, edit.judul, edit.penulis, edit.stok, edit.kategori_id, edit.tahun_terbit);
        @endif
    </script>
@endsection
