@extends('layouts.app')

@section('title', 'Anggota')

@section('content')

    <div class="flex items-center gap-4 mb-6">
        <form method="GET" action="{{ route('anggota.index') }}" class="flex-1">
            <input type="text" name="search" placeholder="Cari anggota..." value="{{ request('search') }}"
                onkeyup="this.form.submit()"
                class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C7F9C]">
        </form>
    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-center border">
            <thead class="bg-[#5C7F9C] text-white">
                <tr>
                    <th class="py-3 border">No</th>
                    <th class="py-3 border">Foto</th>
                    <th class="py-3 border">Nama</th>
                    <th class="py-3 border">Email</th>
                    <th class="py-3 border">Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($anggota as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 border">{{ $loop->iteration }}</td>
                        <td class="py-3 border">
                            @if ($a->photo)
                                <img src="{{ asset('storage/' . $a->photo) }}"
                                    class="w-9 h-9 rounded-full object-cover mx-auto border-2 border-[#5C7F9C]">
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-[#5C7F9C] text-white flex items-center justify-center mx-auto text-sm font-bold">
                                    {{ strtoupper(substr($a->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 border">{{ $a->name }}</td>
                        <td class="py-3 border">{{ $a->email }}</td>
                        <td class="py-3 border">{{ $a->jenis_kelamin }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-gray-500">Data anggota tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $anggota->links() }}
    </div>

@endsection
