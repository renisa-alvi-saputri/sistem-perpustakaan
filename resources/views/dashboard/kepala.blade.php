@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-2xl font-semibold mb-6">
            Hai, {{ auth()->user()->name }} 👋
        </h2>

        <!-- Card Statistik -->
        <div class="flex gap-5 mb-8">

            <!-- JUMLAH BUKU -->
            <div
                class="flex-1 min-w-[250px] bg-gradient-to-r from-indigo-500 to-indigo-400 text-white p-5 rounded-xl flex items-center justify-between shadow-md hover:shadow-lg transition duration-200">
                <div>
                    <h3 class="text-sm font-semibold opacity-90 tracking-wide">JUMLAH BUKU</h3>
                    <p class="text-5xl font-bold mt-2 leading-none">{{ $jumlahBuku ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-book text-6xl opacity-20"></i>
            </div>

            <!-- PETUGAS -->
            <div
                class="flex-1 min-w-[250px] bg-gradient-to-r from-orange-500 to-orange-400 text-white p-5 rounded-xl flex items-center justify-between shadow-md hover:shadow-lg transition duration-200">
                <div>
                    <h3 class="text-sm font-semibold opacity-90 tracking-wide">PETUGAS</h3>
                    <p class="text-5xl font-bold mt-2 leading-none">{{ $jumlahPetugas ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-user-tie text-6xl opacity-20"></i>
            </div>

            <!-- ✅ ANGGOTA -->
            <div
                class="flex-1 min-w-[250px] bg-gradient-to-r from-teal-500 to-teal-400 text-white p-5 rounded-xl flex items-center justify-between shadow-md hover:shadow-lg transition duration-200">
                <div>
                    <h3 class="text-sm font-semibold opacity-90 tracking-wide">ANGGOTA</h3>
                    <p class="text-5xl font-bold mt-2 leading-none">{{ $jumlahAnggota ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-users text-6xl opacity-20"></i>
            </div>

            <!-- PINJAMAN SELESAI -->
            <div
                class="flex-1 min-w-[250px] bg-gradient-to-r from-green-500 to-green-400 text-white p-5 rounded-xl flex items-center justify-between shadow-md hover:shadow-lg transition duration-200">
                <div>
                    <h3 class="text-sm font-semibold opacity-90 tracking-wide">PINJAMAN SELESAI</h3>
                    <p class="text-5xl font-bold mt-2 leading-none">{{ $jumlahPinjaman ?? 0 }}</p>
                </div>
                <i class="fa-solid fa-check-circle text-6xl opacity-20"></i>
            </div>

        </div>

        <!-- DIAGRAM DONAT -->
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2 mb-6">
                <span class="w-2 h-2 rounded-full bg-[#5C7F9C] inline-block"></span>
                Statistik Peminjaman
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

                <!-- Legend — ✅ hanya dipinjam & selesai -->
                <div class="space-y-3">
                    @php
                        $statusList = [
                            'dipinjam' => ['label' => 'Dipinjam', 'color' => '#34D399'],
                            'selesai' => ['label' => 'Selesai', 'color' => '#A78BFA'],
                        ];
                    @endphp

                    @foreach ($statusList as $key => $info)
                        @php $count = $statusData[$key] ?? 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full flex-shrink-0"
                                style="background-color: {{ $info['color'] }}"></span>
                            <span class="text-sm text-gray-600 w-30">{{ $info['label'] }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusData = @json($statusData ?? []);

        // ✅ Hanya dipinjam & selesai
        const labels = ['Dipinjam', 'Selesai'];
        const keys = ['dipinjam', 'selesai'];
        const colors = ['#34D399', '#A78BFA'];
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
