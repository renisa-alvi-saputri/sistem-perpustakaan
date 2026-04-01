@extends('layouts.app')

@section('title', 'Riwayat Pinjam')

@section('content')

    <div class="px-2 py-2">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm text-center border">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 border">No</th>
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
                            <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                            <td class="py-2 px-4 border">
                                @if ($p->status === 'dikembalikan')
                                    {{ $p->tgl_dikembalikan }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-2 px-4 border text-blue-600">{{ $p->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
