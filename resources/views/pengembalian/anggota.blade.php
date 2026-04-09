@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')

    <!-- BUTTON PENGEMBALIAN -->
<div class="mb-6">
    <button onclick="openModal()" class="bg-[#5C7F9C] text-white px-4 py-2 rounded-lg shadow hover:bg-[#4a6d87]">
        + Pengembalian
    </button>
</div>

<!-- MODAL PENGEMBALIAN -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl w-96 shadow-lg">

        <h3 class="text-lg font-semibold text-[#5C7F9C] mb-1">Pengembalian Buku</h3>
        <p class="text-xs text-gray-400 mb-4">Pilih buku yang ingin dikembalikan</p>

        @if ($peminjaman->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-yellow-700 text-sm mb-4">
                Tidak ada buku yang sedang dipinjam.
            </div>
            <div class="flex justify-end">
                <button onclick="closeModal()"
                    class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Tutup</button>
            </div>
        @else
            <form action="{{ route('pengembalian.anggota.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::id() }}" />

                <label class="text-gray-500 text-sm block mb-1">Buku</label>
                <select name="peminjaman_id" required
                    class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-[#5C7F9C] outline-none text-gray-700 mb-4">
                    <option value="">Pilih Buku</option>
                    @foreach ($peminjaman as $item)
                        <option value="{{ $item->id }}">{{ $item->buku->judul }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-1 bg-green-500 text-white rounded-lg hover:bg-green-400">Kembalikan</button>
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
                @forelse ($riwayatPengembalian as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                        <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>
                        <td class="py-2 px-4 border">{{ $p->tgl_kembali }}</td>
                        <td class="py-2 px-4 border text-center">
                            @if ($p->status === 'dikembalikan')
                                <div
                                    class="inline-flex items-center gap-2 bg-yellow-100 border border-yellow-200 px-3 py-1.5 rounded-full">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-yellow-700">
                                        <i class="fa-solid fa-clock"></i>
                                        Menunggu Konfirmasi
                                    </span>
                                </div>
                            @else
                                <div
                                    class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-full">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                        <i class="fa-solid fa-circle-info"></i>
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </div>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-gray-500">
                            Belum ada data pengembalian
                        </td>
                    </tr>
                @endforelse
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
