<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Jadwal Agen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                {{ session('success') }}
            </div>
            @endif

            @if(Auth::user()->role === 'supervisor')
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Tambah Jadwal Baru</h2>
                </header>
                <form method="post" action="{{ route('schedules.store') }}" class="mt-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="user_id" value="Pilih Agen" />
                            <select name="user_id" id="user_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                <option value="">-- Pilih --</option>
                                @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="shift_date" value="Tanggal Shift" />
                            <x-text-input id="shift_date" name="shift_date" type="date" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="shift_type" value="Jenis Shift" />
                            <select name="shift_type" id="shift_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" required>
                                <option value="Pagi">Pagi</option>
                                <option value="Siang">Siang</option>
                                <option value="Malam">Malam</option>
                                <option value="Libur">Libur</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Simpan Jadwal') }}</x-primary-button>
                    </div>
                </form>
            </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header class="mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Jadwal Keseluruhan</h2>
                </header>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama Agen</th>
                                <th class="px-6 py-3">Tanggal Shift</th>
                                <th class="px-6 py-3">Jenis Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $schedule->user->name }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($schedule->shift_date)->format('d F Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 font-semibold leading-tight text-white rounded-full 
                                        @if($schedule->shift_type == 'Pagi') bg-blue-500 
                                        @elseif($schedule->shift_type == 'Siang') bg-yellow-500 
                                        @elseif($schedule->shift_type == 'Malam') bg-gray-800 
                                        @else bg-red-500 @endif">
                                        {{ $schedule->shift_type }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center">Belum ada jadwal yang diinput.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>