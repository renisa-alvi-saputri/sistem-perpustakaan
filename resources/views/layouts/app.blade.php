<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>S-Perpustakaan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ICON TAB -->
    <link rel="icon" href="{{ asset('images/logoapk.png') }}">

    <!-- ICON (PASTI MUNCUL) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-200">

    <!-- LOADING LOGOUT (tersembunyi, muncul saat logout diklik) -->
    <div id="loadingLogout" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
        <div class="bg-white rounded-2xl p-6 flex flex-col items-center gap-3 shadow-lg">
            <svg class="w-10 h-10 text-[#5C7F9C] animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-sm text-[#5C7F9C] font-medium">Keluar...</p>
        </div>
    </div>

    <!-- SIDEBAR -->
    <div class="w-52 bg-gray-100 fixed top-0 left-0 h-full border-r">

        <!-- LOGO -->
        <div class="flex items-center gap-3 h-16 px-4 border-b">
            <img src="{{ asset('images/logoapk.png') }}" class="w-10">
            <div>
                <h2 class="font-bold text-gray-600">S-Perpustakaan</h2>

                @if (auth()->user()->role == 'petugas')
                    <p class="text-sm text-gray-500">Petugas</p>
                @elseif(auth()->user()->role == 'kepala')
                    <p class="text-sm text-gray-500">Kepala</p>
                @elseif(auth()->user()->role == 'anggota')
                    <p class="text-sm text-gray-500">Anggota</p>
                @else
                    <p class="text-sm text-gray-500">User</p>
                @endif

            </div>
        </div>

        <!-- MENU -->
        <ul class="mt-2 text-sm">

            <!-- Dashboard Petugas -->
            @if (auth()->user()->role == 'petugas')
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('dashboard') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-house w-6 text-center"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/kategori"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('kategori') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-list w-6 text-center"></i>
                        Kategori
                    </a>
                </li>

                <li>
                    <a href="/buku"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('buku') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-book w-6 text-center"></i>
                        Buku
                    </a>
                </li>

                <li>
                    <a href="/anggota"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('anggota') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-users w-6 text-center"></i>
                        Anggota
                    </a>
                </li>

                <li>
                    <a href="/peminjaman"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('peminjaman') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-user-pen w-6 text-center"></i>
                        Peminjaman
                    </a>
                </li>

                <li>
                    <a href="/pengembalian"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('pengembalian') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-rotate-left w-6 text-center"></i>
                        Pengembalian
                    </a>
                </li>
            @endif

            <!-- Dashboard Anggota -->
            @if (auth()->user()->role == 'anggota')
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('dashboard') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-house w-6 text-center"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/buku"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('buku') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-book w-6 text-center"></i>
                        Buku
                    </a>
                </li>

                <li>
                    <a href="/peminjaman"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('peminjaman') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-user-pen w-6 text-center"></i>
                        Peminjaman
                    </a>
                </li>

                <li>
                    <a href="/pengembalian"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('pengembalian*') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-rotate-left w-6 text-center"></i>
                        Pengembalian
                    </a>
                </li>

                <li>
                    <a href="/riwayat"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('riwayat') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-center"></i>
                        Riwayat
                    </a>
                </li>
            @endif

            <!-- Dashboard Kepala -->
            @if (auth()->user()->role == 'kepala')
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('dashboard') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-house w-6 text-center"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/petugas"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('petugas') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-user w-6 text-center"></i>
                        Petugas
                    </a>
                </li>

                <li>
                    <a href="/anggota"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('anggota') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-user w-6 text-center"></i>
                        Anggota
                    </a>
                </li>

                <li>
                    <a href="/buku"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('buku') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-book w-6 text-center"></i>
                        Buku
                    </a>
                </li>

                <li>
                    <a href="{{ route('laporan.peminjaman') }}"
                        class="flex items-center gap-3 px-4 py-3 text-lg
        {{ request()->is('laporan/peminjaman') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-chart-bar w-6 text-center"></i>
                        Peminjaman
                    </a>
                </li>

                <li>
                    <a href="{{ route('laporan.pengembalian') }}"
                        class="flex items-center gap-3 px-4 py-3 text-lg
        {{ request()->is('laporan/pengembalian') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">
                        <i class="fa-solid fa-chart-bar w-6 text-center"></i>
                        Pengembalian
                    </a>
                </li>
            @endif

        </ul>
    </div>

    <!-- NAVBAR -->
    <div
        class="fixed top-0 left-52 right-0 h-16 bg-[#5C7F9C] flex items-center justify-between px-6 text-white shadow z-50">

        <h1 class="text-xl font-semibold">
            @yield('title', 'Dashboard')
        </h1>

        <!-- PROFILE -->
        <div class="relative">
            <button onclick="toggleMenu()" class="mt-2">
                @if (auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                        class="w-10 h-10 rounded-full border-2 border-gray-300 object-cover">
                @else
                    <i class="fa-solid fa-user text-2xl text-white"></i>
                @endif
            </button>

            <!-- DROPDOWN -->
            <div id="menu"
                class="hidden absolute right-0 mt-3 w-48 bg-white text-gray-700 rounded-xl shadow-lg overflow-hidden border">

                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100">
                    <i class="fa-solid fa-user"></i>
                    Profile
                </a>

                <div class="border-t"></div>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}" id="formLogout">
                    @csrf
                    <button type="button" onclick="konfirmasiLogout()"
                        class="w-full text-left flex items-center gap-3 px-4 py-3 hover:bg-gray-100">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="ml-52 mt-16 p-6">
        @yield('content')
    </div>

    <!-- SCRIPT -->
    <script>
        function toggleMenu() {
            document.getElementById("menu").classList.toggle("hidden");
        }

        document.addEventListener("click", function(e) {
            const menu = document.getElementById("menu");
            if (!e.target.closest("#menu") && !e.target.closest("button")) {
                menu.classList.add("hidden");
            }
        });

        function konfirmasiLogout() {
            document.getElementById('loadingLogout').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('formLogout').submit();
            }, 1500);
        }
    </script>

</body>

</html>
