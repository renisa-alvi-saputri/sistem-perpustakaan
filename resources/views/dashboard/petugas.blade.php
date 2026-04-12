@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-2xl font-semibold mb-6">
            Hai, {{ auth()->user()->name }} 👋
        </h2>

        <!-- Card Statistik -->
        <div class="grid grid-cols-4 gap-5 mb-8">

            <div
                class="bg-gradient-to-r from-indigo-500 to-indigo-400 text-white p-5 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
                <div>
                    <h3 class="text-sm font-semibold">JUMLAH BUKU</h3>
                    <p class="text-5xl font-bold mt-2">{{ $jumlahBuku ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-book text-6xl opacity-30"></i>
            </div>

            <div
                class="bg-gradient-to-r from-blue-500 to-blue-400 text-white p-5 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
                <div>
                    <h3 class="text-sm font-semibold">KATEGORI</h3>
                    <p class="text-5xl font-bold mt-2">{{ $jumlahKategori ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-book-open text-6xl opacity-30"></i>
            </div>

            <div
                class="bg-gradient-to-r from-teal-500 to-teal-400 text-white p-5 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
                <div>
                    <h3 class="text-sm font-semibold">ANGGOTA</h3>
                    <p class="text-5xl font-bold mt-2">{{ $jumlahAnggota ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-users text-6xl opacity-30"></i>
            </div>

            <div
                class="bg-gradient-to-r from-green-500 to-green-400 text-white p-5 rounded-xl flex justify-between items-center shadow hover:scale-105 transition">
                <div>
                    <h3 class="text-sm font-semibold">PINJAMAN SAAT INI</h3>
                    <p class="text-5xl font-bold mt-2">{{ $jumlahPinjaman ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-user-pen text-6xl opacity-30"></i>
            </div>

        </div>

        <!-- Baris tengah: Menunggu Konfirmasi + Aktivitas Terbaru -->
        <div class="grid grid-cols-5 gap-5 mb-5">

            <!-- TABEL MENUNGGU KONFIRMASI (3/5) -->
            <div class="col-span-3 bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span>
                        Menunggu Konfirmasi
                    </h3>
                    @if (isset($menunggu) && $menunggu->count() > 0)
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-medium">
                            {{ $menunggu->count() }} permintaan
                        </span>
                    @endif
                </div>

                @if (isset($menunggu) && $menunggu->count() > 0)
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-400 border-b">
                                <th class="pb-2 font-medium">Anggota</th>
                                <th class="pb-2 font-medium">Buku</th>
                                <th class="pb-2 font-medium">Tgl Pinjam</th>
                                <th class="pb-2 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($menunggu as $m)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 text-gray-700 font-medium">{{ $m->user->name }}</td>
                                    <td class="py-2.5 text-gray-500 text-xs">{{ Str::limit($m->buku->judul, 25) }}</td>
                                    <td class="py-2.5 text-gray-400 text-xs">
                                        {{ \Carbon\Carbon::parse($m->tgl_pinjam)->format('d M Y') }}</td>
                                    <td class="py-2.5">
                                        <a href="{{ route('peminjaman.index') }}"
                                            class="text-xs text-[#5C7F9C] hover:underline font-medium">Lihat →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                        <i class="fa-solid fa-circle-check text-4xl mb-2"></i>
                        <p class="text-sm">Semua peminjaman sudah dikonfirmasi</p>
                    </div>
                @endif
            </div>

            <!-- AKTIVITAS TERBARU (2/5) -->
            <div class="col-span-2 bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-[#5C7F9C] inline-block"></span>
                    Aktivitas Terbaru
                </h3>

                @if (isset($aktivitas) && $aktivitas->count() > 0)
                    <div class="space-y-3">
                        @foreach ($aktivitas as $a)
                            <div class="flex items-start gap-3">
                                <div class="mt-1.5 flex-shrink-0">
                                    @if ($a->status === 'dipinjam')
                                        <span class="w-2 h-2 rounded-full bg-green-400 block"></span>
                                    @elseif($a->status === 'menunggu')
                                        <span class="w-2 h-2 rounded-full bg-yellow-400 block"></span>
                                    @elseif($a->status === 'dikembalikan')
                                        <span class="w-2 h-2 rounded-full bg-blue-400 block"></span>
                                    @elseif($a->status === 'ditolak')
                                        <span class="w-2 h-2 rounded-full bg-red-400 block"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-green-300 block"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-700 font-medium truncate">{{ $a->user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ Str::limit($a->buku->judul, 28) }}</p>
                                    <p class="text-[10px] text-gray-300 mt-0.5">
                                        {{ \Carbon\Carbon::parse($a->updated_at)->diffForHumans() }}</p>
                                </div>
                                <span
                                    class="flex-shrink-0 text-[10px] px-2 py-0.5 rounded-full
                            @if ($a->status === 'dipinjam') bg-green-100 text-green-700
                            @elseif($a->status === 'menunggu') bg-yellow-100 text-yellow-700
                            @elseif($a->status === 'dikembalikan') bg-blue-100 text-blue-700
                            @elseif($a->status === 'ditolak') bg-red-100 text-red-700
                            @else bg-green-100 text-green-700 @endif">
                                    {{ ucfirst($a->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                        <i class="fa-solid fa-clock-rotate-left text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada aktivitas</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- DIAGRAM DONAT -->
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2 mb-6">
                <span class="w-2 h-2 rounded-full bg-[#5C7F9C] inline-block"></span>
                Statistik Status Peminjaman
            </h3>

            <div class="flex items-center justify-center gap-16">

                <!-- Canvas Donat -->
                <div class="relative w-52 h-52 flex-shrink-0">
                    <canvas id="donatChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <p class="text-3xl font-bold text-gray-700" id="totalDonat">0</p>
                        <p class="text-xs text-gray-400">Total</p>
                    </div>
                </div>

                <!-- Legend -->
                <div class="space-y-3">
                    @php
                        $statusList = [
                            'menunggu' => ['label' => 'Menunggu', 'color' => '#FCD34D'],
                            'dipinjam' => ['label' => 'Dipinjam', 'color' => '#34D399'],
                            'dikembalikan' => ['label' => 'Dikembalikan', 'color' => '#60A5FA'],
                            'ditolak' => ['label' => 'Ditolak', 'color' => '#F87171'],
                            'selesai' => ['label' => 'Selesai', 'color' => '#A78BFA'],
                        ];
                    @endphp

                    @foreach ($statusList as $key => $info)
                        @php $count = $statusData[$key] ?? 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full flex-shrink-0"
                                style="background-color: {{ $info['color'] }}"></span>
                            <span class="text-sm text-gray-600 w-28">{{ $info['label'] }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusData = @json($statusData ?? []);

        const labels = ['Menunggu', 'Dipinjam', 'Dikembalikan', 'Ditolak', 'Selesai'];
        const keys = ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'selesai'];
        const colors = ['#FCD34D', '#34D399', '#60A5FA', '#F87171', '#A78BFA'];
        const data = keys.map(k => statusData[k] ?? 0);
        const total = data.reduce((a, b) => a + b, 0);

        document.getElementById('totalDonat').innerText = total;

        new Chart(document.getElementById('donatChart'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx =>
                                ` ${ctx.label}: ${ctx.raw} (${total > 0 ? Math.round(ctx.raw / total * 100) : 0}%)`
                        }
                    }
                }
            }
        });
    </script>

@endsection
