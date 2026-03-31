@extends('layouts.app')

@section('title', 'Riwayat Pinjam')

@section('content')

<div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full text-sm text-center border">

        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="py-3 border">Buku</th>
                <th class="py-3 border">Tgl Pinjam</th>
                <th class="py-3 border">Tgl Kembali</th>
                <th class="py-3 border">Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($peminjaman as $p)
                @if ($p->user_id == auth()->id())
                    <tr>
                        <td class="border">{{ $p->buku->judul }}</td>
                        <td class="border">{{ $p->tgl_pinjam }}</td>
                        <td class="border">{{ $p->tgl_kembali }}</td>
                        <td class="border text-blue-600">{{ $p->status }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>

    </table>

</div>

@endsection
