@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')

<div class="p-4">

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
                </tr>
            </thead>

            <tbody>
                @forelse ($peminjaman as $p)
                    @if ($p->status == 'dikembalikan')
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border">{{ $p->user->name }}</td>
                            <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                            <td class="py-2 px-4 border">
                                {{ $p->tgl_dikembalikan ?? '-' }}
                            </td>
                            <td class="py-2 px-4 border text-blue-600">
                                {{ $p->status }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-gray-500">
                            Belum ada data pengembalian
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection
