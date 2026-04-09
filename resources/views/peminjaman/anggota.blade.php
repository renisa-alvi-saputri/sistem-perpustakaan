@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')

    <div class="px-2 py-2">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm text-center border">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 border">No</th>
                        <th class="py-3 px-4 border">Buku</th>
                        <th class="py-3 px-4 border">Tgl Pinjam</th>
                        <th class="py-3 px-4 border">Jatuh Tempo</th>
                        <th class="py-3 px-4 border">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($peminjaman as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                            <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>
                            <td class="py-2 px-4 border text-center">
                                @if ($p->status === 'dipinjam')
                                    <div
                                        class="inline-flex items-center gap-2 bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-full">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-700">
                                            <i class="fa-solid fa-book"></i>
                                            Dipinjam
                                        </span>
                                    </div>
                                @elseif ($p->status === 'menunggu')
                                    <div
                                        class="inline-flex items-center gap-2 bg-yellow-100 border border-yellow-200 px-3 py-1.5 rounded-full">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-yellow-700">
                                            <i class="fa-solid fa-clock"></i>
                                            Menunggu Konfirmasi
                                        </span>
                                    </div>
                                @elseif ($p->status === 'ditolak')
                                    <div
                                        class="inline-flex items-center gap-2 bg-red-100 border border-red-200 px-3 py-1.5 rounded-full">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                            <i class="fa-solid fa-xmark"></i>
                                            Ditolak
                                        </span>
                                        @if ($p->alasan_tolak)
                                            <span class="w-px h-3 bg-red-300"></span>
                                            <span class="text-[11px] text-red-500 italic">"{{ $p->alasan_tolak }}"</span>
                                        @endif
                                    </div>
                                @elseif ($p->status === 'dikembalikan')
                                    <div
                                        class="inline-flex items-center gap-2 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                            <i class="fa-solid fa-check"></i>
                                            Dikembalikan
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
                                Tidak ada data peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
