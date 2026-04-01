@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')

    <div class="p-0">

        <!-- BUTTON -->
        <div class="flex gap-4 items-center mb-2">
            <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow hover:bg-[#4a6d87]">
                + Pengembalian
            </button>
        </div>

        <!-- TABEL -->
        <div class="bg-white rounded-xl shadow overflow-hidden mt-6">

            <table class="w-full text-sm text-center border">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 border">No</th>
                        <th class="py-3 px-4 border">Nama</th>
                        <th class="py-3 px-4 border">Buku</th>
                        <th class="py-3 px-4 border">Tgl Pinjam</th>
                        <th class="py-3 px-4 border">Tgl Kembali</th>
                        <th class="py-3 px-4 border">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($peminjaman as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border">{{ $p->user->name }}</td>
                            <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_kembali }}</td>
                            <td class="py-2 px-4 border text-blue-600">{{ $p->status }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

    <!-- ================= MODAL ================= -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

        <div class="bg-white p-6 rounded-xl w-96 shadow-lg">

            <h2 class="text-lg font-semibold mb-4 text-[#5C7F9C]">
                Tambah Pengembalian
            </h2>

            <form method="POST" action="{{ route('pengembalian.store') }}">
                @csrf

                <!-- PILIH NAMA -->
                <div class="mb-3">
                    <label class="text-sm text-gray-600">Pilih Nama</label>
                    <select id="userSelect" class="w-full border p-2 rounded mt-1">
                        <option value="">-- pilih anggota --</option>
                        @foreach ($peminjaman->groupBy('user_id') as $group)
                            <option value="{{ $group->first()->user_id }}">
                                {{ $group->first()->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PILIH BUKU -->
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Pilih Buku</label>
                    <select name="peminjaman_id" id="bukuSelect" class="w-full border p-2 rounded mt-1">
                        <option value="">-- pilih buku --</option>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-400 px-3 py-1 text-white rounded">
                        Batal
                    </button>

                    <button class="bg-green-500 px-3 py-1 text-white rounded hover:bg-green-600">
                        Simpan
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

        const dataPeminjaman = @json($peminjaman);

        document.getElementById('userSelect').addEventListener('change', function() {
            let userId = this.value;
            let bukuSelect = document.getElementById('bukuSelect');

            bukuSelect.innerHTML = '<option value="">-- pilih buku --</option>';

            dataPeminjaman.forEach(function(p) {
                if (p.user_id == userId) {
                    let option = document.createElement('option');
                    option.value = p.id;
                    option.text = p.buku.judul;
                    bukuSelect.appendChild(option);
                }
            });
        });
    </script>

@endsection
