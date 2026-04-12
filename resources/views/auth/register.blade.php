<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <!-- ICON TAB -->
    <link rel="icon" href="{{ asset('images/logoapk.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-[#567C99] h-screen flex items-center justify-center">

    <div class="bg-gray-200 w-96 p-8 rounded-xl shadow-lg text-center">

        <h2 class="text-2xl font-bold mb-3">REGISTER</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-3" autocomplete="off">

            @csrf

            <input type="text" name="name" placeholder="Nama Lengkap"
                class="w-full p-2 border border-gray-300 rounded bg-white">

            <div class="relative">

                <select name="jenis_kelamin" class="w-full p-2 border border-gray-300 rounded bg-white appearance-none">

                    <option value="">Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>

                </select>

                <span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    ▾
                </span>

            </div>


            <input type="email" name="email" placeholder="Email Address" autocomplete="off"
                class="w-full p-2 border border-gray-300 rounded">

            <input type="password" name="password" placeholder="Password" autocomplete="new-password"
                class="w-full p-2 border border-gray-300 rounded bg-white">

            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                class="w-full p-2 border border-gray-300 rounded bg-white">

            <button type="submit" class="w-full bg-[#5C7F9C] text-white p-2 rounded hover:bg-[#4a6d87]">
                Register
            </button>

        </form>

        <p class="mt-3 text-sm">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#5C7F9C]">Login</a>
        </p>

    </div>

</body>

</html>
