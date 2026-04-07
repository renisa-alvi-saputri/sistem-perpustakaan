@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<style>
@media print {
    .no-print {
        display: none !important;
    }
    body {
        margin: 0;
    }
}

#print-wrapper {
    width: 100%;
    max-width: 794px; /* Lebar A4 dalam px */
    margin: 0 auto;
    padding: 10px;
}
#cetak-laporan {
    cursor: pointer;
}
</style>

<div class="max-w-3xl mx-auto mt-6">

    <div id="print-wrapper">

        <div id="print-area" class="bg-white rounded-2xl shadow-lg p-6">

            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                Detail Peminjaman
            </h2>

            <div class="space-y-5 text-sm">
                @foreach ([
                    'Nama Anggota' => $peminjaman->user->name,
                    'Email Anggota' => $peminjaman->user->email,
                    'Judul Buku' => $peminjaman->buku->judul,
                    'Tanggal Pinjam' => \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y'),
                    'Tanggal Kembali' => $peminjaman->tgl_kembali ? \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d F Y') : '-',
                    'Status' => ucfirst($peminjaman->status),
                ] as $label => $value)
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">{{ $label }}</span>
                    <span class="font-medium text-gray-800">{{ $value }}</span>
                </div>
                @endforeach

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Keterlambatan</span>
                    <span class="font-medium text-gray-800">
                        @if ($peminjaman->tgl_kembali)
                            @php
                                $selisih = \Carbon\Carbon::parse($peminjaman->jatuh_tempo)
                                    ->diffInDays($peminjaman->tgl_kembali, false);
                            @endphp
                            {{ $selisih > 0 ? $selisih . ' hari' : '-' }}
                        @else
                            -
                        @endif
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Denda</span>
                    <span class="font-semibold text-red-500">
                        @if ($peminjaman->tgl_kembali)
                            @php
                                $dendaPerHari = 2000;
                                $selisih = \Carbon\Carbon::parse($peminjaman->jatuh_tempo)
                                    ->diffInDays($peminjaman->tgl_kembali, false);
                                $denda = $selisih > 0 ? $selisih * $dendaPerHari : 0;
                            @endphp
                            {{ $denda > 0 ? 'Rp' . number_format($denda, 0, ',', '.') : 'Tidak ada denda' }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between border-t pt-4 no-print">
                <a href="{{ route('laporan.index') }}" class="bg-gray-200 text-gray-800 px-3 py-1.5 text-sm rounded-md hover:bg-gray-300 transition shadow-sm font-medium">
                    Kembali
                </a>
                <button id="cetak-laporan" class="bg-[#5C7F9C] text-white px-4 py-1.5 text-sm rounded-md hover:bg-[#6f93b0] transition shadow font-medium">
                    Cetak PDF
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
document.getElementById("cetak-laporan").addEventListener("click", function () {
    const element = document.getElementById("print-wrapper").cloneNode(true);

    // Hapus tombol cetak
    element.querySelectorAll(".no-print").forEach(el => el.remove());

    html2pdf().set({
        margin: 0,
        filename: "peminjaman-{{ $peminjaman->id }}.pdf",
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
            scale: 2,
            width: 794,
            scrollY: 0
        },
        jsPDF: {
            unit: 'px',
            format: [794, 1123],
            orientation: 'portrait'
        }
    }).from(element).save();
});
</script>

@endsection
