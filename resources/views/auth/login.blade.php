<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- ICON TAB -->
    <link rel="icon" href="{{ asset('images/logoapk.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#567C99] h-screen flex items-center justify-center">

    <div class="bg-gray-100 w-96 p-8 rounded-xl shadow-lg text-center">

        <!-- Logo -->
        <img src="{{ asset('images/logoapk.png') }}" class="w-24 mx-auto -mt-4 mb-2">

        <!-- Judul -->
        <h2 class="text-xl font-bold text-gray-700 mb-1">S-Perpustakaan</h2>

        <h3 class="mb-2 text-gray-500 text-sm">Masukkan Email dan Password</h3>

        <form method="POST" action="{{ route('login') }}" class="space-y-3" autocomplete="off">

            @csrf

            <input type="email" name="email" placeholder="Email Address" autocomplete="off"
                class="w-full p-2 border border-gray-300 rounded">

            <input type="password" name="password" placeholder="Password" autocomplete="new-password"
                class="w-full p-2 border border-gray-300 rounded">

            <button type="submit" class="w-full bg-[#5C7F9C] text-white p-2 rounded hover:bg-[#4a6d87]">
                Login
            </button>

        </form>

        <p class="mt-3 text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#5C7F9C]">Register</a>
        </p>

    </div>

</body>

</html>
