@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')

    <div class="flex gap-4 mb-6 items-center">
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow">
            + Peminjaman
        </button>
    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-center border">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="py-3 px-4 border">No</th>
                    <th class="py-3 px-4 border">Nama</th>
                    <th class="py-3 px-4 border">Buku</th>
                    <th class="py-3 px-4 border">Tgl Pinjam</th>
                    <th class="py-3 px-4 border">Tgl Kembali</th>
                    <th class="py-3 px-4 border">Status</th>
                    <th class="py-3 px-4 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($peminjaman as $p)
                    <tr>
                        <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border">{{ $p->user->name }}</td>
                        <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_kembali }}</td>
                        <td class="py-2 px-4 border text-blue-600">{{ $p->status }}</td>

                        <td class="border">
                            <div class="flex justify-center gap-2">

                                <!-- EDIT -->
                                <button
                                    onclick="editPinjam({{ $p->id }}, '{{ $p->user_id }}', '{{ $p->buku_id }}', '{{ $p->tgl_pinjam }}', '{{ $p->tgl_kembali }}')"
                                    class="bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded">
                                    edit
                                </button>

                                <!-- DELETE -->
                                <form action="{{ route('peminjaman.destroy', $p->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-100 text-red-600 px-2 py-0.5 rounded">
                                        hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

        <div class="bg-white p-6 rounded-xl w-96">

            <h2 class="text-lg font-semibold mb-4">Tambah Peminjaman</h2>

            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf

                <select name="user_id" class="w-full border p-2 mb-2 rounded">
                    @foreach ($anggota as $a)
                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                    @endforeach
                </select>

                <select name="buku_id" class="w-full border p-2 mb-2 rounded">
                    @foreach ($buku as $b)
                        <option value="{{ $b->id }}">{{ $b->judul }}</option>
                    @endforeach
                </select>

                <input type="date" name="tgl_pinjam" class="w-full border p-2 mb-2 rounded">
                <input type="date" name="tgl_kembali" class="w-full border p-2 mb-2 rounded">

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-400 px-3 py-1 text-white rounded">
                        Batal
                    </button>
                    <button class="bg-[#5C7F9C] px-3 py-1 text-white rounded">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

        <div class="bg-white p-6 rounded-xl w-96">

            <h2 class="text-lg font-semibold mb-4">Edit Peminjaman</h2>

            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')

                <select name="user_id" id="editUser" class="w-full border p-2 mb-2 rounded">
                    @foreach ($anggota as $a)
                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                    @endforeach
                </select>

                <select name="buku_id" id="editBuku" class="w-full border p-2 mb-2 rounded">
                    @foreach ($buku as $b)
                        <option value="{{ $b->id }}">{{ $b->judul }}</option>
                    @endforeach
                </select>

                <input type="date" id="editPinjam" name="tgl_pinjam" class="w-full border p-2 mb-2 rounded">
                <input type="date" id="editKembali" name="tgl_kembali" class="w-full border p-2 mb-2 rounded">

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
        function openModal() {
            document.getElementById('modal').classList.remove('hidden')
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden')
        }

        function editPinjam(id, user_id, buku_id, tgl_pinjam, tgl_kembali) {
            document.getElementById('modalEdit').classList.remove('hidden');

            document.getElementById('editUser').value = user_id;
            document.getElementById('editBuku').value = buku_id;
            document.getElementById('editPinjam').value = tgl_pinjam;
            document.getElementById('editKembali').value = tgl_kembali;

            document.getElementById('formEdit').action = "/peminjaman/" + id;
        }

        function closeEdit() {
            document.getElementById('modalEdit').classList.add('hidden')
        }
    </script>

@endsection
