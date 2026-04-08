@extends('layouts.app')

@section('title', 'Pengembalian')

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
                    <th class="py-3 px-4 border">Tgl Kembali</th>
                    <th class="py-3 px-4 border">Denda</th>
                    <th class="py-3 px-4 border">Konfirmasi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($peminjaman as $p)
                    @if (in_array($p->status, ['dikembalikan', 'selesai']))
                        <tr>
                            <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border">{{ $p->user->name }}</td>
                            <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                            <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_kembali ?? '-' }}</td>
                            <td class="py-2 px-4 border">
                                @php
                                    $dendaPerHari = 2000;
                                    $jatuhTempo = \Carbon\Carbon::parse($p->jatuh_tempo);
                                    $tglKembali = $p->tgl_kembali
                                        ? \Carbon\Carbon::parse($p->tgl_kembali)
                                        : \Carbon\Carbon::now();

                                    $selisih = $jatuhTempo->diffInDays($tglKembali, false);
                                    $denda = $selisih > 0 ? $selisih * $dendaPerHari : 0;
                                @endphp

                                <span class="{{ $denda > 0 ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                    {{ $denda > 0 ? 'Rp' . number_format($denda, 0, ',', '.') : '-' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border text-center">
                                @if ($p->status == 'dikembalikan')
                                    <!-- Tombol Konfirmasi Selesai -->
                                    <form action="{{ route('peminjaman.selesai', $p->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-800 gap-1 hover:bg-gray-300">
                                            Konfirmasi Pengembalian
                                        </button>
                                    </form>
                                @else
                                    <!-- Status Selesai -->
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-green-200 text-green-800 gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif
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

@endsection
