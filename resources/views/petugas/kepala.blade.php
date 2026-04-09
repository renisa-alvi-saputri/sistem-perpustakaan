@extends('layouts.app')

@section('title', 'Petugas')

@section('content')
    <div class="flex items-center gap-4 mb-6">

        <!-- BUTTON TAMBAH PETUGAS -->
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow">
            + Petugas
        </button>

        <!-- SEARCH -->
        <form method="GET" action="{{ route('petugas.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari petugas..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>

    </div>

    <!-- TABEL PETUGAS -->
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
                @foreach ($petugas as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 border">{{ $loop->iteration }}</td>
                        <td class="py-3 border">{{ $p->name }}</td>
                        <td class="py-3 border">{{ $p->email }}</td>
                        <td class="py-3 border">{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="py-3 border">
                            <div class="flex justify-center gap-2">

                                <button
                                    onclick="editPetugas({{ $p->id }}, '{{ $p->name }}', '{{ $p->email }}', '{{ $p->jenis_kelamin }}')"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">

                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>

                                    Edit
                                </button>

                                <button onclick="openDelete({{ $p->id }})"
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

    <!-- ================= MODAL TAMBAH PETUGAS ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-2xl w-96 shadow-xl">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-5">
                Tambah Petugas
            </h2>

            <form method="POST" action="{{ route('petugas.store') }}" autocomplete="off" class="space-y-4">
                @csrf

                <!-- TRIK ANTI AUTOFILL -->
                <input type="text" name="fakeuser" class="hidden">
                <input type="password" name="fakepassword" class="hidden">

                <!-- Nama -->
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Nama</label>
                    <input type="text" name="name" placeholder="Masukkan nama" autocomplete="off"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Email</label>
                    <input type="email" name="email" placeholder="Masukkan email" autocomplete="new-email"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" autocomplete="new-password"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        autocomplete="new-password"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <!-- BUTTON -->
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


    <!-- ================= MODAL EDIT PETUGAS ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl w-96 shadow-xl">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-5">Edit Petugas</h2>

            <form method="POST" id="formEdit" autocomplete="off" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Nama</label>
                    <input type="text" id="editName" name="name"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Email</label>
                    <input type="email" id="editEmail" name="email"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Jenis Kelamin</label>
                    <select id="editJK" name="jenis_kelamin"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Password Baru (Opsional)</label>
                    <input type="password" id="editPassword" name="password"
                        placeholder="Kosongkan jika tidak ingin mengubah"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEdit()"
                        class="px-4 py-1.5 text-sm rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition">
                        Batal
                    </button>
                    <button
                        class="px-4 py-1.5 text-sm rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition shadow">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>


    <!-- ================= MODAL DELETE PETUGAS ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white p-6 rounded-2xl w-80 shadow-xl text-center">

            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-2">Hapus Petugas</h3>
            <p class="text-sm text-gray-500 mb-6">Yakin ingin menghapus petugas ini?</p>

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

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden')
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden')
        }

        function editPetugas(id, name, email, jk) {
            document.getElementById('modalEdit').classList.remove('hidden')
            document.getElementById('editName').value = name
            document.getElementById('editEmail').value = email
            document.getElementById('editJK').value = jk
            document.getElementById('editPassword').value = ''
            document.getElementById('formEdit').action = "/petugas/" + id
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden')
        }

        function openDelete(id) {
            document.getElementById('modalDelete').classList.remove('hidden')
            document.getElementById('formDelete').action = "/petugas/" + id
        }

        function closeDelete() {
            document.getElementById('modalDelete').classList.add('hidden')
        }
    </script>

@endsection
