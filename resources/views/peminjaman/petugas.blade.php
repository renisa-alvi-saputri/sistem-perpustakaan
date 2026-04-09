@extends('layouts.app')

@section('title', 'Peminjaman')

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

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-center border">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="py-3 px-4 border">No</th>
                    <th class="py-3 px-4 border">Nama</th>
                    <th class="py-3 px-4 border">Buku</th>
                    <th class="py-3 px-4 border">Tgl Pinjam</th>
                    <th class="py-3 px-4 border">Jatuh Tempo</th>
                    <th class="py-3 px-4 border">Konfirmasi</th>
                    <th class="py-3 px-4 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($peminjaman as $p)
                    <tr>
                        <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border">{{ $p->user->name }}</td>
                        <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                        <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>

                        <td class="py-2 px-4 border text-center">
                            @if ($p->status == 'menunggu')
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Konfirmasi -->
                                    <form action="{{ route('peminjaman.konfirmasi', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-full text-xs font-medium text-gray-700 hover:bg-gray-200">
                                            <i class="fa-solid fa-check"></i>
                                            Konfirmasi
                                        </button>
                                    </form>

                                    <!-- Tombol Tolak -->
                                    <button type="button"
                                        onclick="openTolak({{ $p->id }})"
                                        class="inline-flex items-center gap-1 bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-full text-xs font-medium text-gray-700 hover:bg-gray-200">
                                        <i class="fa-solid fa-xmark"></i>
                                        Tolak
                                    </button>
                                </div>
                            @elseif ($p->status == 'dipinjam')
                                <div
                                    class="inline-flex items-center gap-2 bg-green-100 border border-green-200 px-3 py-1.5 rounded-full">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                        <i class="fa-solid fa-check"></i>
                                        Dikonfirmasi
                                    </span>
                                </div>
                            @elseif ($p->status == 'ditolak')
                                <div
                                    class="inline-flex items-center gap-2 bg-red-100 border border-red-200 px-3 py-1.5 rounded-full">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                        <i class="fa-solid fa-xmark"></i>
                                        Ditolak
                                    </span>
                                </div>
                            @endif
                        </td>

                        <!-- AKSI -->
                        <td class="py-2 px-4 border">
                            <div class="flex justify-center gap-2">

                                <!-- EDIT -->
                                <button
                                    onclick="editPinjam({{ $p->id }}, '{{ $p->tgl_pinjam }}', '{{ $p->jatuh_tempo }}')"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>

                                <!-- DELETE -->
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
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-gray-500">Tidak ada data peminjaman</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- ================= MODAL TOLAK ================= -->
    <div id="modalTolak" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

            <h2 class="text-lg font-semibold text-[#5C7F9C] mb-1">Tolak Peminjaman</h2>
            <p class="text-xs text-gray-400 mb-4">Alasan akan ditampilkan ke anggota</p>

            <form id="formTolak" method="POST">
                @csrf
                @method('PUT')

                <label class="text-gray-500 text-sm block mb-1">Alasan Penolakan</label>
                <textarea name="alasan_tolak" id="alasanTolak" rows="3" placeholder="Contoh: Stok buku sedang dalam perbaikan..."
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none text-sm resize-none"></textarea>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="closeTolak()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-1 bg-red-500 text-white rounded-lg hover:bg-red-400">Tolak</button>
                </div>
            </form>

        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-96">
            <h2 class="text-lg font-semibold mb-4 text-[#5C7F9C]">Edit Peminjaman</h2>
            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')

                <div class="mb-2">
                    <label class="text-sm text-gray-600">Tanggal Pinjam</label>
                    <input type="date" id="editPinjam" name="tgl_pinjam"
                        class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-600">Jatuh Tempo</label>
                    <input type="date" id="editKembali" name="jatuh_tempo"
                        class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEdit()"
                        class="bg-gray-400 px-3 py-1 text-white rounded-lg hover:bg-gray-500">Batal</button>
                    <button class="bg-yellow-500 px-3 py-1 text-white rounded-lg hover:bg-yellow-400">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL DELETE ================= -->
    <div id="modalDelete" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-80 shadow text-center">
            <h3 class="text-lg font-semibold text-[#5C7F9C] mb-3">Hapus Peminjaman</h3>
            <p class="text-sm text-gray-600 mb-6">Yakin ingin menghapus peminjaman ini?</p>

            <form id="formDelete" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDelete()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-1 bg-red-500 text-white rounded-lg hover:bg-red-400">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        function openTolak(id) {
            document.getElementById('modalTolak').classList.remove('hidden');
            document.getElementById('alasanTolak').value = '';
            document.getElementById('formTolak').action = "/peminjaman/" + id + "/tolak";
        }

        function closeTolak() {
            document.getElementById('modalTolak').classList.add('hidden');
        }

        function editPinjam(id, tglPinjam, tglKembali) {
            document.getElementById('modalEdit').classList.remove('hidden');
            document.getElementById('editPinjam').value = tglPinjam;
            document.getElementById('editKembali').value = tglKembali;
            document.getElementById('formEdit').action = "/peminjaman/" + id;
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        function openDelete(id) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('formDelete').action = "/peminjaman/" + id;
        }

        function closeDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>

@endsection
