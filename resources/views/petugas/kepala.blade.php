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
                <th class="py-3 border">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($petugas as $p)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 border">{{ $loop->iteration }}</td>
                    <td class="py-3 border">{{ $p->name }}</td>
                    <td class="py-3 border">{{ $p->email }}</td>
                    <td class="py-3 border">
                        <div class="flex justify-center gap-2">

                            <button onclick="editPetugas({{ $p->id }}, '{{ $p->name }}', '{{ $p->email }}')"
                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">
                                Edit
                            </button>

                            <button onclick="openDelete({{ $p->id }})"
                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-red-100 text-red-800 hover:bg-red-200 gap-0.5">
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
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white p-5 rounded-2xl w-96 shadow-lg">

        <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Tambah Petugas</h2>

        <form method="POST" action="{{ route('petugas.store') }}" class="space-y-2">
            @csrf

            <!-- Nama -->
            <label for="name" class="text-gray-500 text-sm block mb-1">Nama</label>
            <input type="text" name="name" id="name" placeholder="Masukkan nama"
                class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

            <!-- Email -->
            <label for="email" class="text-gray-500 text-sm block mb-1">Email</label>
            <input type="email" name="email" id="email" placeholder="Masukkan email"
                class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

            <!-- Password -->
            <label for="password" class="text-gray-500 text-sm block mb-1">Password</label>
            <input type="password" name="password" id="password" placeholder="Password"
                class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

            <label for="password_confirmation" class="text-gray-500 text-sm block mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
                class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

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

<!-- ================= MODAL EDIT PETUGAS ================= -->
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

        <h2 class="text-xl font-semibold mb-4 text-[#5C7F9C]">Edit Petugas</h2>

        <form method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <label for="editNama" class="text-gray-500 text-sm block mb-1">Nama</label>
            <input type="text" id="editNama" name="name" placeholder="Nama"
                class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

            <!-- Email -->
            <label for="editEmail" class="text-gray-500 text-sm block mt-2 mb-1">Email</label>
            <input type="email" id="editEmail" name="email" placeholder="Email"
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

<!-- ================= MODAL DELETE ================= -->
<div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl w-80 shadow text-center">

        <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">
            Hapus Petugas
        </h3>

        <p class="text-sm text-gray-600 mb-6">
            Yakin ingin menghapus petugas ini?
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
function editPetugas(id, nama, email) {
    document.getElementById('modalEdit').classList.remove('hidden')
    document.getElementById('editNama').value = nama
    document.getElementById('editEmail').value = email
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
