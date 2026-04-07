@extends('layouts.app')

@section('title', 'Laporan')

@section('content')

    <!-- Tombol Cetak Laporan / Download PDF -->
    <div class="mb-3 ml-2">
        <button id="cetak-laporan"
            class="bg-[#5C7F9C]/90 text-white px-4 py-2 text-sm rounded-lg
        hover:bg-[#5C7F9C] transition shadow-sm flex items-center gap-2">
            🖨️ Cetak Laporan
        </button>
    </div>

    <!-- AREA TABEL YANG AKAN DICETAK / PDF -->
    <div class="area-print px-2 py-2">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm text-center border">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 border">No</th>
                        <th class="py-3 px-4 border">Nama Anggota</th>
                        <th class="py-3 px-4 border">Buku</th>
                        <th class="py-3 px-4 border">Tgl Pinjam</th>
                        <th class="py-3 px-4 border">Jatuh Tempo</th>
                        <th class="py-3 px-4 border">Tgl Kembali</th>
                        <th class="py-3 px-4 border">Denda</th>
                        <th class="py-3 px-4 border">Status</th>
                        <th class="py-3 px-4 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($peminjaman as $p)
                        @if ($p->status === 'selesai')
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border">{{ $no++ }}</td>
                                <td class="py-2 px-4 border">{{ $p->user->name }}</td>
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
                                <td class="py-2 px-4 border">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">
                                        Selesai
                                    </span>
                                </td>
                                <td class="py-2 px-4 border text-center">
                                    <button onclick="window.location='{{ route('laporan.detail', $p->id) }}'"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
               bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors duration-200 gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

<!-- CSS PRINT & STYLE FIX -->
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
    }
</style>

<!-- SCRIPT HTML2PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("cetak-laporan").addEventListener("click", () => {
            const element = document.querySelector(".area-print");

            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const filename = `laporan-peminjaman-${yyyy}${mm}${dd}.pdf`;

            html2pdf().set({
                margin: [0.2, 0.2, 0.2, 0.2], // margin in inch
                filename: filename,
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
                    orientation: 'landscape' // supaya semua kolom muat
                },
                pagebreak: {
                    mode: ['css', 'legacy']
                } // biar tabel panjang pecah halaman
            }).from(element).save();
        });
    });
</script>
