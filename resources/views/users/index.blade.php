<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Tambah Agen Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="mb-4">
                <a href="{{ route('users.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                    &larr; Kembali ke Daftar Agen
                </a>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6">
                <ul class="list-disc ml-5 text-sm font-bold">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Agen</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Gunakan nama lengkap sesuai KTP/identitas operasional.</p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Email ini akan digunakan agen untuk login. Pastikan aktif.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-md">
                        <p class="text-sm text-blue-800 font-medium">
                            <span class="font-bold">Informasi:</span> Kata sandi (password) default untuk akun baru ini adalah: <code class="bg-blue-100 px-2 py-1 rounded font-mono text-blue-900">password123</code>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">Agen dapat mengubah password ini nanti setelah mereka berhasil login.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 font-bold text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold text-sm shadow-sm transition">
                            Simpan Agen Baru
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>