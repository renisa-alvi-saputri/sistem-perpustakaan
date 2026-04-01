<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Perpustakaan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ICON (PASTI MUNCUL) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-200">

    <!-- SIDEBAR -->
    <div class="w-52 bg-gray-100 fixed top-0 left-0 h-full border-r">

        <!-- LOGO -->
        <div class="flex items-center gap-3 h-16 px-4 border-b">
            <img src="{{ asset('images/logoapk.png') }}" class="w-10">
            <div>
                <h2 class="font-bold text-gray-600">E-Perpustakaan</h2>

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

                <!-- Kategori -->
                <li>
                    <a href="/kategori"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('kategori') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-list w-6 text-center"></i>
                        Kategori
                    </a>
                </li>

                <!-- Buku -->
                <li>
                    <a href="/buku"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('buku') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-book w-6 text-center"></i>
                        Buku
                    </a>
                </li>


                <!-- Anggota -->
                <li>
                    <a href="/anggota"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('anggota') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-users w-6 text-center"></i>
                        Anggota
                    </a>
                </li>

                <!-- Peminjaman -->
                <li>
                    <a href="/peminjaman"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('peminjaman') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-user-pen w-6 text-center"></i>
                        Peminjaman
                    </a>
                </li>

                <!-- Pengembalian -->
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

                <!-- Buku -->
                <li>
                    <a href="/buku"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('buku') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-book w-6 text-center"></i>
                        Buku
                    </a>
                </li>

                <!-- Pinjam Buku -->
                <li>
                    <a href="/peminjaman"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('peminjaman') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-user-pen w-6 text-center"></i>
                        Riwayat Pinjam
                    </a>
                </li>

                <!-- Pengembalian -->
                <li>
                    <a href="/pengembalian"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('pengembalian*') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-rotate-left w-6 text-center"></i>
                        Pengembalian
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

                <!-- Buku -->
                <li>
                    <a href="/buku"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('buku') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-book w-6 text-center"></i>
                        Buku
                    </a>
                </li>

                <!-- Peminjaman -->
                <li>
                    <a href="/peminjaman"
                        class="flex items-center gap-3 px-4 py-3 text-lg
            {{ request()->is('peminjaman') ? 'text-gray-400 font-semibold border-l-4 border-gray-400 pl-3' : 'text-gray-600 hover:text-gray-400' }}">

                        <i class="fa-solid fa-user-pen w-6 text-center"></i>
                        Peminjaman
                    </a>
                </li>
            @endif

        </ul>
    </div>

    <!-- NAVBAR -->
    <div class="fixed top-0 left-52 right-0 h-16 bg-[#5C7F9C] flex items-center justify-between px-6 text-white shadow">

        <h1 class="text-xl font-semibold">
            @yield('title', 'Dashboard')
        </h1>

        <!-- PROFILE -->
        <div class="relative">
            <button onclick="toggleMenu()" class="mt-2">
                @if (auth()->user()->photo)
                    <img src="{{ asset('foto_profile/' . auth()->user()->photo) }}"
                        class="w-10 h-10 rounded-full border-2 border-gray-300 object-cover">
                @else
                    <i class="fa-solid fa-user text-2xl text-white"></i>
                @endif
            </button>

            <!-- DROPDOWN -->
            <div id="menu"
                class="hidden absolute right-0 mt-3 w-48 bg-white text-gray-700 rounded-xl shadow-lg overflow-hidden border">

                <!-- PROFILE -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100">
                    <i class="fa-solid fa-user"></i>
                    Profile
                </a>

                <div class="border-t"></div>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left flex items-center gap-3 px-4 py-3 hover:bg-gray-100">
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
    </script>

</body>

</html>
