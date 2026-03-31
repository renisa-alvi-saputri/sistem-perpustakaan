    @extends('layouts.app')

    @section('title', 'Profile')

    @section('content')
        <div class="flex justify-center mt-6">

    <div class="bg-white p-6 rounded-xl shadow w-full max-w-4xl">

        <h2 class="text-xl font-semibold mb-6">Profile</h2>

        <div class="flex gap-6">

            <!-- FOTO KIRI -->
            <div class="w-1/3 flex flex-col items-center mt-10">

                @if (auth()->user()->photo)
                    <img src="{{ asset('foto_profile/' . auth()->user()->photo) }}"
                        class="w-40 h-40 object-cover rounded-full border shadow">
                @else
                    <div
                        class="w-40 h-40 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 text-sm">
                        No Image
                    </div>
                @endif

            </div>

            <!-- FORM KANAN -->
            <div class="w-2/3">

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Nama -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}"
                            class="w-full h-10 px-3 border rounded bg-gray-100 text-sm focus:outline-none">
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full h-10 px-3 border rounded bg-gray-100 text-sm focus:outline-none">
                            <option value="Laki-laki"
                                {{ auth()->user()->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan"
                                {{ auth()->user()->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}"
                            class="w-full h-10 px-3 border rounded bg-gray-100 text-sm focus:outline-none">
                    </div>

                    <!-- Upload Foto -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1">Ganti Foto</label>
                        <input type="file" name="photo" class="text-sm">
                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-2">
                        <a href="/dashboard"
                            class="bg-gray-500 text-white px-3 py-1.5 text-sm rounded hover:bg-gray-400">
                            Kembali
                        </a>

                        <button
                            class="bg-green-500 text-white px-3 py-1.5 text-sm rounded hover:bg-green-600">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
    @endsection
