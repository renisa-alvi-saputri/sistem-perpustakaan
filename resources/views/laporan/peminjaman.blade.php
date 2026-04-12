@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')

    <!-- Filter di luar kotak putih -->
    <form method="GET" action="{{ route('laporan.peminjaman') }}" class="flex items-end gap-4 mb-4 flex-wrap no-print">
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
                LAPORAN PEMINJAMAN BUKU
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
                            <th class="py-3 px-4 border border-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $p)
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
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium
                                        bg-green-100 text-green-800 border border-green-200">
                                        Dipinjam
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-gray-400">Tidak ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
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
                filename: `laporan-peminjaman-${yyyy}${mm}${dd}.pdf`,
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
