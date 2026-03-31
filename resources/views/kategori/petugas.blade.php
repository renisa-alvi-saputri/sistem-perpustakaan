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
                    <th class="py-3 border">Opsi</th>
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
                                    class="bg-yellow-200 text-yellow-800 px-3 py-1 text-xs rounded-full leading-none">
                                    edit
                                </button>

                                <button onclick="openDelete({{ $k->id }})"
                                    class="bg-red-100 text-red-600 px-3 py-1 text-xs rounded-full leading-none">
                                    hapus
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

        <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-4 text-[#5C7F9C]">
                Tambah Kategori
            </h2>

            <form method="POST" action="{{ route('kategori.store') }}" class="space-y-3">
                @csrf

                <input type="text" name="nama_kategori" placeholder="Nama kategori"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <div class="flex justify-end gap-2 pt-2">

                    <button type="button" onclick="closeModal()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">
                        Batal
                    </button>

                    <button class="px-4 py-1 bg-green-500 text-white rounded-lg hover:bg-green-400">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- ================= MODAL EDIT KATEGORI ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-4 text-[#5C7F9C]">
                Edit Kategori
            </h2>

            <form method="POST" id="formEdit" class="space-y-3">
                @csrf
                @method('PUT')

                <input type="text" name="nama_kategori" id="editNama"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <div class="flex justify-end gap-2 pt-2">

                    <button type="button" onclick="closeEdit()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">
                        Batal
                    </button>

                    <button class="px-4 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400">
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
@endsection
