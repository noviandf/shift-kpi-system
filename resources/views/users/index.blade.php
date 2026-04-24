<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                {{ __('Manajemen Agen') }}
            </h2>
            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm transition">
                + Tambah Agen Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-slate-200">Daftar Agen Operasional</h3>
                </div>

                <div class="overflow-x-auto relative">
                    <table class="w-full text-sm text-left whitespace-nowrap text-slate-600 dark:text-slate-400">
                        <thead class="text-xs text-gray-700 dark:text-slate-300 uppercase bg-gray-100 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 border-b dark:border-slate-700">No</th>
                                <th scope="col" class="px-6 py-3 border-b dark:border-slate-700">Nama Agen</th>
                                <th scope="col" class="px-6 py-3 border-b dark:border-slate-700">Email (Login)</th>
                                <th scope="col" class="px-6 py-3 border-b dark:border-slate-700">Tanggal Bergabung</th>
                                <th scope="col" class="px-6 py-3 border-b dark:border-slate-700 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-800">
                            @forelse($users as $index => $user)
                            <tr class="bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition duration-150">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-slate-100">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-slate-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900 border border-amber-200 dark:border-amber-800 px-3 py-1 rounded text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun agen ini? Semua jadwal dan KPI yang terikat pada akun ini akan ikut terhapus!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 border border-red-200 dark:border-red-800 px-3 py-1 rounded text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-slate-500 italic bg-gray-50 dark:bg-slate-900">
                                    Belum ada data agen yang terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>