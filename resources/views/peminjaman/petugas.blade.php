@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')

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
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border">{{ $p->user->name }}</td>
                        <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                        <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>
                        <td class="py-2 px-4 border text-center">
                            @if ($p->status == 'menunggu')
                                <!-- Bisa dicentang untuk konfirmasi -->
                                <form action="{{ route('peminjaman.konfirmasi', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="checkbox" name="konfirmasi" onchange="this.form.submit()"
                                        class="h-5 w-5 accent-gray-600">
                                </form>
                            @elseif ($p->status == 'dipinjam')
                                <!-- Status dipinjam, dicentang tapi disabled -->
                                <input type="checkbox" checked disabled class="h-5 w-5 accent-gray-600">
                            @else
                                <!-- Status dikembalikan / selesai -->
                                <input type="checkbox" checked disabled class="h-5 w-5 accent-gray-600">
                            @endif
                        </td>

                        <!-- AKSI -->
                        <td class="py-2 px-4 border">
                            <div class="flex justify-center gap-2">

                                <button
                                    onclick="editPinjam({{ $p->id }}, '{{ $p->tgl_pinjam }}', '{{ $p->tgl_kembali }}')"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-yellow-100 text-yellow-800 hover:bg-yellow-200 gap-0.5">

                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>

                                    Edit
                                </button>
                                <form action="{{ route('peminjaman.destroy', $p->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] bg-red-100 text-red-800 hover:bg-red-200 gap-0.5">

                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>

                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-gray-500">
                            Tidak ada data peminjaman
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

        <div class="bg-white p-6 rounded-xl w-96">

            <h2 class="text-lg font-semibold mb-4">Edit Peminjaman</h2>

            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')

                <div class="mb-2">
                    <label class="text-sm text-gray-600">Tanggal Pinjam</label>
                    <input type="date" id="editPinjam" name="tgl_pinjam" class="w-full border p-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-600">Tanggal Kembali</label>
                    <input type="date" id="editKembali" name="tgl_kembali" class="w-full border p-2 rounded">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEdit()" class="bg-gray-400 px-3 py-1 text-white rounded">
                        Batal
                    </button>
                    <button class="bg-yellow-500 px-3 py-1 text-white rounded">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        function editPinjam(id, user_id, buku_id, tgl_pinjam, tgl_kembali) {
            document.getElementById('modalEdit').classList.remove('hidden');

            document.getElementById('editPinjam').value = tgl_pinjam;
            document.getElementById('editKembali').value = tgl_kembali;

            document.getElementById('formEdit').action = "/peminjaman/" + id;
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden')
        }
    </script>

@endsection
