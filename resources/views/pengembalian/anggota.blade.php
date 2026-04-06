@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')

    <!-- BUTTON + MODAL PENGEMBALIAN ANGGOTA -->
    <div class="mb-6">
        <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow hover:bg-[#4a6d87]">
            + Pengembalian
        </button>
    </div>

    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl w-96 shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-[#5C7F9C] text-center">Pengembalian</h3>

            @if ($peminjaman->isEmpty())
                <div class="p-4 bg-yellow-100 border border-yellow-300 rounded text-yellow-700 mb-4">Tidak ada buku yang
                    sedang dipinjam.</div>
                <div class="flex justify-end">
                    <button onclick="closeModal()" class="bg-gray-400 px-3 py-1 text-white rounded">Tutup</button>
                </div>
            @else
                <form action="{{ route('pengembalian.anggota.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ Auth::id() }}" />

                    <div class="mb-4">
                        <select name="peminjaman_id" required class="w-full border p-2 rounded mt-1 text-gray-700">
                            <option value="" class="text-gray-400">Pilih Buku</option>
                            @foreach ($peminjaman as $item)
                                <option value="{{ $item->id }}">{{ $item->buku->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal()"
                            class="bg-gray-400 px-3 py-1 text-white rounded hover:bg-gray-500">Batal</button>
                        <button type="submit"
                            class="bg-green-500 px-3 py-1 text-white rounded hover:bg-green-600">Kembalikan</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- TABEL BUKU YANG DIPINJAM ANGGOTA -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-center border">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="py-3 px-4 border">No</th>
                    <th class="border">Buku</th>
                    <th class="border">Tgl Pinjam</th>
                    <th class="border">Jatuh Tempo</th>
                    <th class="border">Tgl Kembali</th>
                    <th class="border">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($riwayatPengembalian as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                        <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_kembali }}</td>
                        <td class="py-2 px-4 border">
                            @if ($p->status === 'dikembalikan')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-200 text-yellow-800">
                                     ⏳ Menunggu
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                                    {{ ucfirst($p->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>


@endsection
