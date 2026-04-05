@extends('layouts.app')

@section('title', 'Laporan')

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
                        <th class="py-3 px-4 border">Tgl Kembali</th>
                        <th class="py-3 px-4 border">Denda</th>
                        <th class="py-3 px-4 border">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($peminjaman as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border">{{ $p->buku->judul }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_pinjam }}</td>
                            <td class="py-2 px-4 border">{{ $p->jatuh_tempo }}</td>
                            <td class="py-2 px-4 border">{{ $p->tgl_kembali }}</td>
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
                            <td class="py-2 px-4 border text-blue-600">{{ $p->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
