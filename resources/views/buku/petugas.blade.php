@extends('layouts.app')

@section('title', 'Buku')

@section('content')

    {{-- LOADING + TOAST NOTIFIKASI --}}
    @if (session('success'))
        <div id="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
            <div class="bg-white rounded-2xl p-6 flex flex-col items-center gap-3 shadow-lg">
                <svg class="w-10 h-10 text-[#5C7F9C] animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm text-[#5C7F9C] font-medium">Memproses...</p>
            </div>
        </div>

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

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm text-center border">

            <thead class="bg-[#5C7F9C] uppercase text-xs text-white">
                <tr>
                    <th class="py-3 border">No</th>
                    <th class="py-3 border">Cover</th>
                    <th class="py-3 border">Judul</th>
                    <th class="py-3 border">Penulis</th>
                    <th class="py-3 border">Kategori</th>
                    <th class="py-3 border">Stok</th>
                    <th class="py-3 border">Tahun</th>
                    <th class="py-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($buku as $b)
                    <tr>

                        <td class="py-4 border w-16 text-center">{{ $loop->iteration }}</td>

                        <td class="py-4 border">
                            <img src="{{ $b->cover ? asset('cover/' . $b->cover) : 'https://picsum.photos/80/120' }}"
                                class="w-16 h-24 object-contain rounded mx-auto">
                        </td>

                        <td class="py-4 border text-center">{{ $b->judul }}</td>
                        <td class="py-4 border text-center">{{ $b->penulis }}</td>
                        <td class="py-4 border text-center">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                        <td class="py-4 border">{{ $b->stok }}</td>
                        <td class="py-4 border">{{ $b->tahun_terbit ?? '-' }}</td>

                        <td class="py-4 border">
                            <div class="flex justify-center items-center gap-2">

                                <!-- DETAIL -->
                                <button
                                    onclick="showDetail('{{ $b->id }}', '{{ $b->judul }}', '{{ $b->penulis }}', '{{ $b->stok }}', '{{ $b->kategori->nama_kategori ?? '-' }}', '{{ $b->cover }}', '{{ $b->tahun_terbit }}')"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-800 hover:bg-blue-200 gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Detail
                                </button>

                                <!-- EDIT -->
                                <button
                                    onclick="editBuku({{ $b->id }}, '{{ $b->judul }}', '{{ $b->penulis }}', {{ $b->stok }}, {{ $b->kategori_id }}, {{ $b->tahun_terbit ?? 0 }})"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>

                                <!-- DELETE -->
                                <button onclick="openDelete({{ $b->id }})"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-red-100 text-red-800 hover:bg-red-200 gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus
                                </button>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Tambah Buku</h2>

            <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data" class="space-y-2">
                @csrf

                <label class="text-gray-500 text-sm block mb-1">Judul Buku</label>
                <input type="text" name="judul" placeholder="Masukkan judul buku" value="{{ old('judul') }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <label class="text-gray-500 text-sm block mb-1">Penulis</label>
                <input type="text" name="penulis" placeholder="Masukkan nama penulis"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mb-1">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" placeholder="Masukkan tahun terbit"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mb-1">Stok</label>
                <input type="number" name="stok" placeholder="Masukkan jumlah stok"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mb-1">Kategori</label>
                <select name="kategori_id"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <label class="text-gray-500 text-sm block mb-1">Cover Buku</label>
                <input type="file" name="cover" class="w-full text-sm">

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-1 bg-green-500 text-white rounded-lg hover:bg-green-400">Simpan</button>
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

                <label class="text-gray-500 text-sm block mb-1">Judul Buku</label>
                <input type="text" id="editJudul" name="judul" placeholder="Masukkan judul buku"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <label class="text-gray-500 text-sm block mb-1">Penulis</label>
                <input type="text" id="editPenulis" name="penulis" placeholder="Masukkan nama penulis"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mb-1">Tahun Terbit</label>
                <input type="number" id="editTahunTerbit" name="tahun_terbit" placeholder="Masukkan tahun terbit"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mb-1">Stok</label>
                <input type="number" id="editStok" name="stok" placeholder="Masukkan jumlah stok"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mb-1">Kategori</label>
                <select id="editKategori" name="kategori_id"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <label class="text-gray-500 text-sm block mb-1">Cover Buku</label>
                <input type="file" name="cover" class="w-full text-sm">

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEdit()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400">Update</button>
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

            <button onclick="closeDetail()"
                class="mt-4 bg-gray-400 text-white px-4 py-1 rounded-lg hover:bg-gray-500">Tutup</button>
        </div>
    </div>

    <!-- ================= MODAL DELETE ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-80 shadow text-center">

            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">Hapus Buku</h3>
            <p class="text-sm text-gray-600 mb-6">Yakin ingin menghapus buku ini?</p>

            <form id="formDelete" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDelete()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button class="px-4 py-1 bg-red-500 text-white rounded-lg hover:bg-red-400">Hapus</button>
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
            document.getElementById('detailCover').src = cover ?
                "/cover/" + cover : "https://picsum.photos/120/160";
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

        @if ($errors->has('judul') && !session('edit'))
            openModal();
        @endif

        @if (session('edit'))
            let edit = @json(session('edit'));
            editBuku(edit.id, edit.judul, edit.penulis, edit.stok, edit.kategori_id, edit.tahun_terbit);
        @endif
    </script>

@endsection
