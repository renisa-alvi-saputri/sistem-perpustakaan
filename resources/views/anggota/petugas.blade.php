@extends('layouts.app')

@section('title', 'Anggota')

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
            + Anggota
        </button>
        <form method="GET" action="{{ route('anggota.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari anggota..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>
    </div>

    <!-- TABEL -->
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
                @foreach ($anggota as $a)
                    <tr>
                        <td class="py-3 border">{{ $loop->iteration }}</td>
                        <td class="py-3 border">
                            <!-- ✅ Tampilkan foto atau avatar default -->
                            @if ($a->photo)
                                <img src="{{ asset('storage/' . $a->photo) }}"
                                    class="w-9 h-9 rounded-full object-cover mx-auto border-2 border-[#5C7F9C]">
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-[#5C7F9C] text-white flex items-center justify-center mx-auto text-sm font-bold">
                                    {{ strtoupper(substr($a->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 border">{{ $a->name }}</td>
                        <td class="py-3 border">{{ $a->email }}</td>
                        <td class="py-3 border">{{ $a->jenis_kelamin }}</td>
                        <td class="py-3 border">
                            <div class="flex justify-center gap-2">
                                <button
                                    onclick="editAnggota({{ $a->id }}, '{{ $a->name }}', '{{ $a->email }}', '{{ $a->jenis_kelamin }}')"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="openDelete({{ $a->id }})"
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

    <!-- Pagination -->
    <div class="mt-4">
        {{ $anggota->links() }}
    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-2xl w-96 shadow-lg max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-2 text-[#5C7F9C]">Tambah Anggota</h2>

            <form method="POST" action="{{ route('anggota.store') }}" autocomplete="off" enctype="multipart/form-data"
                class="space-y-2"> {{-- ✅ enctype wajib untuk upload --}}
                @csrf

                <label class="text-gray-500 text-sm block mb-1">Nama</label>
                <input type="text" name="name" placeholder="Masukkan nama" value="{{ old('name') }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                <label class="text-gray-500 text-sm block mb-1">Email</label>
                <input type="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                <label class="text-gray-500 text-sm block mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                    </option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                    </option>
                </select>

                <label class="text-gray-500 text-sm block mb-1">Password</label>
                <input type="password" name="password" placeholder="Masukkan password" autocomplete="new-password"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                <label class="text-gray-500 text-sm block mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password"
                    autocomplete="new-password"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- ✅ Upload Foto -->
                <label class="text-gray-500 text-sm block mb-1">Foto Profil (Opsional)</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full border p-2 rounded-lg text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#5C7F9C] file:text-white hover:file:bg-[#4a6b85]">
                @error('photo')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

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
        <div class="bg-white p-6 rounded-2xl w-96 shadow-lg max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4 text-[#5C7F9C]">Edit Anggota</h2>

            @if ($errors->any() && old('_method') == 'PUT')
                <div class="bg-red-100 text-red-700 text-sm px-3 py-2 rounded-lg mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" id="formEdit" autocomplete="off" enctype="multipart/form-data"> {{-- ✅ enctype --}}
                @csrf
                @method('PUT')

                <label class="text-gray-500 text-sm block mb-1">Nama</label>
                <input type="text" id="editNama" name="name" placeholder="Nama"
                    value="{{ old('_method') == 'PUT' ? old('name') : '' }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mt-2 mb-1">Email</label>
                <input type="email" id="editEmail" name="email" placeholder="Email"
                    value="{{ old('_method') == 'PUT' ? old('email') : '' }}"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <label class="text-gray-500 text-sm block mt-2 mb-1">Jenis Kelamin</label>
                <select id="editJK" name="jenis_kelamin"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

                <label class="text-gray-500 text-sm block mt-2 mb-1">Password Baru (Opsional)</label>
                <input type="password" id="editPassword" name="password"
                    placeholder="Kosongkan jika tidak ingin mengubah" autocomplete="new-password" readonly
                    onfocus="this.removeAttribute('readonly')"
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">

                <!-- ✅ Upload Foto Baru -->
                <label class="text-gray-500 text-sm block mt-2 mb-1">Ganti Foto (Opsional)</label>
                <div id="fotoPreviewEdit" class="mb-2 hidden">
                    <img id="imgPreviewEdit" src=""
                        class="w-16 h-16 rounded-full object-cover border-2 border-[#5C7F9C]">
                </div>
                <input type="file" name="photo" accept="image/*" id="editFotoInput"
                    onchange="previewFotoEdit(this)"
                    class="w-full border p-2 rounded-lg text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#5C7F9C] file:text-white hover:file:bg-[#4a6b85]">

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeEdit()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button class="px-4 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL DELETE ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-80 shadow text-center">
            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">Hapus Anggota</h3>
            <p class="text-sm text-gray-600 mb-6">Yakin ingin menghapus anggota ini?</p>
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

    <script>
        @if ($errors->any() && old('_method') != 'PUT')
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modal').classList.remove('hidden');
            });
        @endif

        @if ($errors->any() && old('_method') == 'PUT')
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modalEdit').classList.remove('hidden');
                document.getElementById('editPassword').value = '';
                const url = "{{ url()->previous() }}";
                const match = url.match(/\/anggota\/(\d+)/);
                if (match) {
                    document.getElementById('formEdit').action = '/anggota/' + match[1];
                }
            });
        @endif

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
            document.getElementById('editPassword').value = ''
            document.getElementById('formEdit').action = "/anggota/" + id
            // Reset preview foto
            document.getElementById('fotoPreviewEdit').classList.add('hidden')
            document.getElementById('editFotoInput').value = ''
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

        // ✅ Preview foto sebelum disimpan
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
