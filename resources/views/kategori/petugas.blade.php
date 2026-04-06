@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="flex gap-4 mb-6 items-center">

        <!-- BUTTON -->
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow">
            + Kategori
        </button>

        <!-- SEARCH -->
        <input type="text" placeholder="Cari nama kategori..."
            class="border px-4 py-2 rounded-lg flex-1 focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">

    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm text-center border">

            <thead class="bg-gray-100 uppercase text-xs text-gray-600">
                <tr>
                    <th class="py-3 border">No</th>
                    <th class="py-3 border">Nama Kategori</th>
                    <th class="py-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $k)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 border">{{ $loop->iteration }}</td>

                        <td class="py-4 border">
                            {{ $k->nama_kategori }}
                        </td>

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
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Tambah Kategori</h2>

            <form method="POST" action="{{ route('kategori.store') }}" class="space-y-2">
                @csrf

                <!-- Label dan Input Nama Kategori -->
                <input type="text" name="nama_kategori" id="nama_kategori" placeholder="Masukkan Nama Kategori"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none"
                    value="{{ old('nama_kategori') }}">

                <!-- Pesan Error -->
                @error('nama_kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

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

    <!-- ================= MODAL EDIT KATEGORI ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Edit Kategori</h2>

            <form method="POST" id="formEdit" class="space-y-2">
                @csrf
                @method('PUT')

                <!-- Nama Kategori -->
                <label for="editNama" class="text-gray-500 text-sm block mb-1">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="editNama"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none"
                    placeholder="Masukkan Nama Kategori">

                <!-- Pesan Error jika duplikat -->
                @error('nama_kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

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

    <!-- ================= MODAL DELETE KATEGORI ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-2xl w-80 shadow-lg text-center">

            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">
                Hapus Kategori
            </h3>

            <p class="text-sm text-gray-600 mb-6">
                Yakin ingin menghapus kategori ini?
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

    <!-- SCRIPT -->
    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        // EDIT
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
    </script>

    <!-- ================= SCRIPT MODAL ================= -->
    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        // Jika ada error, buka modal otomatis
        @if ($errors->has('nama_kategori'))
            openModal();
        @endif
    </script>

@endsection
