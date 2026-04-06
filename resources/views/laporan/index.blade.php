@extends('layouts.app')

@section('title', 'Laporan')

@section('content')

<!-- Tombol Cetak Laporan / Download PDF (di atas tabel) -->
<div class="mb-3 ml-2">
    <button id="cetak-laporan"
        class="bg-white text-gray-700 px-4 py-2 text-sm rounded-lg hover:bg-gray-200 transition shadow-sm flex items-center gap-2">
        🖨️ Cetak Laporan
    </button>
</div>

<!-- AREA TABEL YANG AKAN DICETAK / PDF -->
<div class="area-print">
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
                    @php $no = 1; @endphp
                    @foreach ($peminjaman as $p)
                        @if ($p->status === 'selesai')
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border">{{ $no++ }}</td>
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">
                                        ✅ Selesai
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

<!-- CSS PRINT -->
<style>
@media print {
    body * { visibility: hidden; }
    .area-print, .area-print * { visibility: visible; }
    .area-print { position: absolute; left: 0; top: 0; width: 100%; }
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

        const opt = {
            margin: 0.5,
            filename: filename,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    });
});
</script>
