@extends('layouts.app')

@section('title', 'Anggota')

@section('content')
    <div class="flex items-center gap-4 mb-6">

        <!-- BUTTON -->
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow whitespace-nowrap">
            + Anggota
        </button>

        <!-- SEARCH -->
        <form method="GET" action="{{ route('anggota.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari anggota..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>

    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm text-center border">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="py-3 border">No</th>
                    <th class="py-3 border">Nama</th>
                    <th class="py-3 border">Email</th>
                    <th class="py-3 border">Jenis Kelamin</th>
                    <th class="py-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($anggota as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 border">{{ $loop->iteration }}</td>
                        <td class="py-3 border">{{ $a->name }}</td>
                        <td class="py-3 border">{{ $a->email }}</td>
                        <td class="py-3 border">{{ $a->jenis_kelamin }}</td>
                        <td class="py-3 border">
                            <div class="flex justify-center gap-2">

                                <button
                                    onclick="editAnggota({{ $a->id }}, '{{ $a->name }}', '{{ $a->email }}', '{{ $a->jenis_kelamin }}')"
                                    class="bg-yellow-200 text-yellow-800 px-2 py-0.5 text-xs rounded-full">
                                    edit
                                </button>

                                <button onclick="openDelete({{ $a->id }})"
                                    class="bg-red-100 text-red-600 px-2 py-0.5 text-xs rounded-full">
                                    hapus
                                </button>

                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <!-- ================= MODAL TAMBAH ================ -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-4 text-[#5C7F9C]">
                Tambah Anggota
            </h2>

            <form method="POST" action="{{ route('anggota.store') }}" class="space-y-3">
                @csrf

                <input type="text" name="name" placeholder="Nama"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <input type="email" name="email" placeholder="Email"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <select name="jenis_kelamin"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

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
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

            <h2 class="text-xl font-semibold mb-4 text-[#5C7F9C]">
                Edit Anggota
            </h2>

            <form method="POST" id="formEdit" class="space-y-3">
                @csrf
                @method('PUT')

                <input type="text" id="editNama" name="name" placeholder="Nama"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <input type="email" id="editEmail" name="email" placeholder="Email"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <select id="editJK" name="jenis_kelamin"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

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

    <!-- ================= MODAL DELETE ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-xl w-80 shadow text-center">

            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">
                Hapus Anggota
            </h3>

            <p class="text-sm text-gray-600 mb-6">
                Yakin ingin menghapus anggota ini?
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

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden')
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden')
        }

        function editAnggota(id, nama, email, jenis_kelamin) {
            document.getElementById('modalEdit').classList.remove('hidden')
            document.getElementById('editNama').value = nama
            document.getElementById('editEmail').value = email
            document.getElementById('editJK').value = jenis_kelamin
            document.getElementById('formEdit').action = "/anggota/" + id
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden')
        }

        function openDelete(id) {
            document.getElementById('modalDelete').classList.remove('hidden')
            document.getElementById('formDelete').action = "/anggota/" + id
        }

        function closeDelete() {
            document.getElementById('modalDelete').classList.add('hidden')
        }
    </script>
@endsection
