@extends('layouts.app')

@section('title', 'Petugas')

@section('content')

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

    <div class="flex items-center gap-4 mb-6">
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow">
            + Petugas
        </button>
        <form method="GET" action="{{ route('petugas.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari petugas..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>
    </div>

    <!-- TABEL PETUGAS -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-center border">
            <thead class="bg-[#5C7F9C] text-white">
                <tr>
                    <th class="py-3 border">No</th>
                    <th class="py-3 border">Foto</th>
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
                        <td class="py-3 border">
                            @if ($p->photo)
                                <img src="{{ asset('storage/' . $p->photo) }}"
                                    class="w-9 h-9 rounded-full object-cover mx-auto border-2 border-[#5C7F9C]">
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-[#5C7F9C] text-white flex items-center justify-center mx-auto text-sm font-bold">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 border">{{ $p->name }}</td>
                        <td class="py-3 border">{{ $p->email }}</td>
                        <td class="py-3 border">{{ $p->jenis_kelamin }}</td>
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
        <div class="bg-white p-6 rounded-2xl w-96 shadow-xl max-h-[90vh] overflow-y-auto">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-5">Tambah Petugas</h2>

            <form method="POST" action="{{ route('petugas.store') }}" autocomplete="off" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <input type="text" name="fakeuser" class="hidden">
                <input type="password" name="fakepassword" class="hidden">

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Nama</label>
                    <input type="text" name="name" placeholder="Masukkan nama" value="{{ old('name') }}"
                        autocomplete="off"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Email</label>
                    <input type="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}"
                        autocomplete="new-email"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" autocomplete="new-password"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        autocomplete="new-password"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                {{-- ✅ Upload Foto --}}
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Foto Profil (Opsional)</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full border px-3 py-2 rounded-lg text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#5C7F9C] file:text-white hover:file:bg-[#4a6b85]">
                    @error('photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
        <div class="bg-white p-6 rounded-2xl w-96 shadow-xl max-h-[90vh] overflow-y-auto">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-5">Edit Petugas</h2>

            <form method="POST" id="formEdit" autocomplete="off" enctype="multipart/form-data" class="space-y-4">
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
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Password Baru (Opsional)</label>
                    <input type="password" id="editPassword" name="password"
                        placeholder="Kosongkan jika tidak ingin mengubah"
                        class="w-full h-10 px-3 border rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                {{-- ✅ Ganti Foto --}}
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Ganti Foto (Opsional)</label>
                    <div id="fotoPreviewEdit" class="mb-2 hidden">
                        <img id="imgPreviewEdit" src=""
                            class="w-12 h-12 rounded-full object-cover border-2 border-[#5C7F9C]">
                    </div>
                    <input type="file" name="photo" accept="image/*" id="editFotoInput"
                        onchange="previewFotoEdit(this)"
                        class="w-full border px-3 py-2 rounded-lg text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#5C7F9C] file:text-white hover:file:bg-[#4a6b85]">
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
        @if ($errors->any() && !session('edit_error'))
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modal').classList.remove('hidden');
            });
        @endif

        function openModal() {
            document.getElementById('modal').classList.remove('hidden')
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden')
        }

        function editPetugas(id, name, email, jk) {
            document.getElementById('editName').value = name
            document.getElementById('editEmail').value = email
            document.getElementById('editPassword').value = ''
            document.getElementById('formEdit').action = "/petugas/" + id
            document.getElementById('fotoPreviewEdit').classList.add('hidden')
            document.getElementById('editFotoInput').value = ''

            const select = document.getElementById('editJK')
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === jk) {
                    select.selectedIndex = i
                    break
                }
            }

            document.getElementById('modalEdit').classList.remove('hidden')
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

        function previewFotoEdit(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader()
                reader.onload = e => {
                    document.getElementById('imgPreviewEdit').src = e.target.result
                    document.getElementById('fotoPreviewEdit').classList.remove('hidden')
                }
                reader.readAsDataURL(input.files[0])
            }
        }
    </script>

@endsection
