@extends('layouts.app')

@section('title', 'Laporan Pengembalian')

@section('content')

    <!-- Filter di luar kotak putih -->
    <form method="GET" action="{{ route('laporan.pengembalian') }}" class="flex items-end gap-4 mb-4 flex-wrap no-print">
        <div>
            <input type="date" name="dari" value="{{ $dari ?? '' }}"
                class="border px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] outline-none bg-white">
        </div>
        <div>
            <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                class="border px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-[#5C7F9C] outline-none bg-white">
        </div>
        <button type="submit"
            class="flex items-center gap-2 bg-[#5C7F9C] text-white px-5 py-2 rounded-lg text-sm hover:bg-[#4a6b85] transition">

            <i class="fa-solid fa-filter"></i>
            Filter
        </button>

        @if ($dari || $sampai)
            <a href="{{ route('laporan.peminjaman') }}"
                class="flex items-center gap-2 bg-gray-500 text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-700 transition">

                <i class="fa-solid fa-rotate-left"></i>
                Reset
            </a>
        @endif
        <button type="button" id="cetak-laporan"
            class="flex items-center gap-2 bg-red-500 text-white px-5 py-2 rounded-lg text-sm hover:bg-red-600 transition">

            <i class="fa-solid fa-print"></i>
            Cetak PDF
        </button>
    </form>

    <div class="bg-gray-50 rounded-xl shadow p-6">
        <div class="area-print">

            <!-- Judul ikut tercetak -->
            <h2 class="text-xl font-bold text-center text-gray-700 tracking-widest mb-4">
                LAPORAN PENGEMBALIAN BUKU
            </h2>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center border border-gray-300">
                    <thead>
                        <tr class="bg-[#5C7F9C] text-white">
                            <th class="py-3 px-4 border border-gray-300">No</th>
                            <th class="py-3 px-4 border border-gray-300">Nama</th>
                            <th class="py-3 px-4 border border-gray-300">Judul Buku</th>
                            <th class="py-3 px-4 border border-gray-300">Tanggal Pinjam</th>
                            <th class="py-3 px-4 border border-gray-300">Jatuh Tempo</th>
                            <th class="py-3 px-4 border border-gray-300">Tanggal Kembali</th>
                            <th class="py-3 px-4 border border-gray-300">Terlambat</th>
                            <th class="py-3 px-4 border border-gray-300">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalDenda = 0; @endphp
                        @forelse ($peminjaman as $p)
                            @php
                                $dendaPerHari = 2000;
                                $jatuhTempo = \Carbon\Carbon::parse($p->jatuh_tempo);
                                $tglKembali = $p->tgl_kembali
                                    ? \Carbon\Carbon::parse($p->tgl_kembali)
                                    : \Carbon\Carbon::now();
                                $selisih = $jatuhTempo->diffInDays($tglKembali, false);
                                $denda = $selisih > 0 ? $selisih * $dendaPerHari : 0;
                                $totalDenda += $denda;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border border-gray-200">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border border-gray-200">{{ $p->user->name }}</td>
                                <td class="py-2 px-4 border border-gray-200">{{ $p->buku->judul }}</td>
                                <td class="py-2 px-4 border border-gray-200">
                                    {{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}
                                </td>
                                <td class="py-2 px-4 border border-gray-200">
                                    {{ \Carbon\Carbon::parse($p->jatuh_tempo)->format('d M Y') }}
                                </td>
                                <td class="py-2 px-4 border border-gray-200">
                                    {{ $p->tgl_kembali ? \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-2 px-4 border border-gray-200">
                                    {{ $selisih > 0 ? $selisih . ' hari' : '0 hari' }}
                                </td>
                                <td
                                    class="py-2 px-4 border border-gray-200 font-semibold
                                    {{ $denda > 0 ? 'text-red-500' : 'text-gray-500' }}">
                                    Rp {{ number_format($denda, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-gray-400">Tidak ada data pengembalian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($peminjaman->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="7" class="py-3 px-4 text-right font-bold border border-gray-300">
                                    Total Denda
                                </td>
                                <td class="py-3 px-4 font-bold text-red-500 border border-gray-300">
                                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .area-print,
            .area-print * {
                visibility: visible;
            }

            .area-print {
                position: relative;
                width: 100%;
                left: 0;
                top: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                word-wrap: break-word;
            }

            .no-print {
                display: none;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById("cetak-laporan").addEventListener("click", () => {
            const element = document.querySelector(".area-print");
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            html2pdf().set({
                margin: [0.3, 0.3, 0.3, 0.3],
                filename: `laporan-pengembalian-${yyyy}${mm}${dd}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 2,
                    scrollY: 0
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'landscape'
                },
                pagebreak: {
                    mode: ['css', 'legacy']
                }
            }).from(element).save();
        });
    </script>

@endsection
