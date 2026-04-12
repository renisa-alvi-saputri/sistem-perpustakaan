@extends('layouts.app')

@section('title', 'Kategori')

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
            + Kategori
        </button>

        <!-- SEARCH -->
        <form method="GET" action="{{ route('kategori.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari nama kategori..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="border px-4 py-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>

    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm text-center border">

            <thead class="bg-[#5C7F9C] uppercase text-xs text-white">
                <tr>
                    <th class="py-3 border">No</th>
                    <th class="py-3 border">Nama Kategori</th>
                    <th class="py-3 border">Aksi</th>

                </tr>
            </thead>

            <tbody>
                @foreach ($data as $k)
                    <tr>
                        <td class="py-4 border">{{ $loop->iteration }}</td>

                        <td class="py-4 border">{{ $k->nama_kategori }}</td>

                        <td class="py-4 border">
                            <div class="flex justify-center items-center gap-2">

                                <button onclick="editKategori({{ $k->id }}, '{{ $k->nama_kategori }}')"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>

                                <button onclick="openDelete({{ $k->id }})"
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

    <!-- ================= MODAL TAMBAH KATEGORI ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl w-96 shadow-xl">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-5">
                Tambah Kategori
            </h2>

            <form method="POST" action="{{ route('kategori.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" placeholder="Masukkan nama kategori"
                        value="{{ old('nama_kategori') }}"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                @error('nama_kategori')
                    <p class="text-red-500 text-sm -mt-2">{{ $message }}</p>
                @enderror

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-1.5 text-sm rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-1.5 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600 transition shadow">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>


    <!-- ================= MODAL EDIT KATEGORI ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl w-96 shadow-xl">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-5">
                Edit Kategori
            </h2>

            <form method="POST" id="formEdit" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="editNama" placeholder="Masukkan nama kategori"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                @error('nama_kategori')
                    <p class="text-red-500 text-sm -mt-2">{{ $message }}</p>
                @enderror

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEdit()"
                        class="px-4 py-1.5 text-sm rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-1.5 text-sm rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition shadow">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>


    <!-- ================= MODAL DELETE KATEGORI ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl w-80 shadow-xl text-center">

            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-2">
                Hapus Kategori
            </h3>

            <p class="text-sm text-gray-500 mb-6">
                Yakin ingin menghapus kategori ini?
            </p>

            <form id="formDelete" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDelete()"
                        class="px-4 py-1.5 text-sm rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
                        Batal
                    </button>

                    <button
                        class="px-4 py-1.5 text-sm rounded-lg bg-red-500 text-white hover:bg-red-600 transition shadow">
                        Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function editKategori(id, nama) {
            document.getElementById('modalEdit').classList.remove('hidden');
            document.getElementById('editNama').value = nama;
            document.getElementById('formEdit').action = "/kategori/" + id;
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        function openDelete(id) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('formDelete').action = "/kategori/" + id;
        }

        function closeDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }

        @if ($errors->has('nama_kategori'))
            openModal();
        @endif
    </script>

@endsection
