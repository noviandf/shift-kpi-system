<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Penilaian KPI Agen') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="p-4 mb-4 text-sm font-medium text-emerald-800 dark:text-emerald-200 rounded-lg bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800">
                {{ session('success') }}
            </div>
            @endif

            @if(Auth::user()->role === 'supervisor')
            <div class="p-6 sm:p-8 bg-white dark:bg-slate-900 shadow-sm sm:rounded-lg border border-slate-200 dark:border-slate-800">
                <header>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Input Nilai Bulan Ini</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Skor Akhir dan Grade akan dihitung secara rasional oleh sistem. Pastikan nilai berada di rentang 0-100.</p>
                </header>

                <form method="post" action="{{ route('performances.store') }}" class="mt-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="user_id" value="Pilih Agen" class="font-bold text-slate-700 dark:text-slate-300" />
                            <select name="user_id" id="user_id" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full mt-1" required>
                                <option value="" disabled selected>-- Pilih Agen --</option>
                                @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="evaluation_month" value="Bulan Evaluasi" class="font-bold text-slate-700 dark:text-slate-300" />
                            <select name="evaluation_month" id="evaluation_month" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full mt-1" required>
                                <option value="" disabled selected>-- Pilih Bulan --</option>
                                @php
                                for ($i = -1; $i < 11; $i++) {
                                    $date=\Carbon\Carbon::now()->subMonths($i);
                                    $monthValue = $date->translatedFormat('F Y');
                                    echo "<option value=\"{$monthValue}\">{$monthValue}</option>";
                                    }
                                    @endphp
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-slate-100 dark:border-slate-800 pt-6">
                        <div>
                            <x-input-label for="qa_raw" value="Skor QA (0-100)" class="font-bold text-slate-700 dark:text-slate-300" />
                            <input id="qa_raw" name="qa_raw" type="number" step="0.01" min="0" max="100" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="attendance_raw" value="Persentase Kehadiran (0-100)" class="font-bold text-slate-700 dark:text-slate-300" />
                            <input id="attendance_raw" name="attendance_raw" type="number" step="0.01" min="0" max="100" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="aht_raw" value="Skor AHT (0-100)" class="font-bold text-slate-700 dark:text-slate-300" />
                            <input id="aht_raw" name="aht_raw" type="number" step="0.01" min="0" max="100" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm mt-1 block w-full" required />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 border-t border-slate-100 dark:border-slate-800 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:bg-blue-700 active:bg-blue-900 transition ease-in-out duration-150 shadow-sm">
                            {{ __('Hitung & Simpan KPI') }}
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <div class="bg-white dark:bg-slate-900 shadow-sm sm:rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
                <header class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Riwayat Penilaian KPI</h2>

                    <form method="GET" action="{{ route('performances.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">
                        @if(Auth::user()->role === 'supervisor')
                        <input type="text" name="search" placeholder="Cari nama agen..." value="{{ request('search') }}" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 text-sm rounded-md shadow-sm h-9 w-full md:w-40 focus:border-blue-500 focus:ring-blue-500">
                        @endif

                        <select name="filter_month" class="border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 text-sm rounded-md shadow-sm h-9 w-full md:w-36 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Bulan</option>
                            @php
                            for ($i = -1; $i < 11; $i++) {
                                $date=\Carbon\Carbon::now()->subMonths($i);
                                $monthValue = $date->translatedFormat('F Y');
                                $selected = request('filter_month') == $monthValue ? 'selected' : '';
                                echo "<option value=\"{$monthValue}\" {$selected}>{$monthValue}</option>";
                                }
                                @endphp
                        </select>

                        <button type="submit" class="px-3 py-2 bg-slate-800 dark:bg-slate-700 border border-transparent rounded-md font-bold text-xs text-white uppercase hover:bg-slate-700 dark:hover:bg-slate-600 transition shadow-sm h-9">
                            Filter
                        </button>

                        @if(request()->anyFilled(['search', 'filter_month']))
                        <a href="{{ route('performances.index') }}" class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md font-bold text-xs text-slate-700 dark:text-slate-300 uppercase hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm h-9 flex items-center">
                            Reset
                        </a>
                        @endif

                        <button type="submit" name="export" value="csv" class="px-3 py-2 bg-emerald-600 dark:bg-emerald-500 border border-transparent rounded-md font-bold text-xs text-white uppercase hover:bg-emerald-700 dark:hover:bg-emerald-600 transition shadow-sm h-9 flex items-center justify-center w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            CSV
                        </button>
                    </form>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 dark:text-slate-400">
                        <thead class="text-xs text-slate-800 dark:text-slate-300 uppercase bg-slate-100 dark:bg-slate-800/50">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-slate-800">Nama Agen</th>
                                <th class="px-6 py-4 border-b dark:border-slate-800">Bulan</th>
                                <th class="px-6 py-4 text-center border-b dark:border-slate-800">QA</th>
                                <th class="px-6 py-4 text-center border-b dark:border-slate-800">Hadir</th>
                                <th class="px-6 py-4 text-center border-b dark:border-slate-800">AHT</th>
                                <th class="px-6 py-4 text-center border-b dark:border-slate-800">Final Score</th>
                                <th class="px-6 py-4 border-b dark:border-slate-800">Grade</th>
                                <th class="px-6 py-4 border-b dark:border-slate-800">Dinilai Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($performances as $perf)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-200">{{ $perf->user->name }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">{{ $perf->evaluation_month }}</td>
                                <td class="px-6 py-4 text-center font-medium">{{ $perf->qa_raw }}</td>
                                <td class="px-6 py-4 text-center font-medium">{{ $perf->attendance_raw }}%</td>
                                <td class="px-6 py-4 text-center font-medium">{{ $perf->aht_raw }}</td>
                                <td class="px-6 py-4 text-center font-black text-slate-900 dark:text-slate-100">{{ $perf->final_score }}</td>
                                <td class="px-6 py-4 font-bold 
                                    {{ str_starts_with($perf->grade, 'A') ? 'text-emerald-600 dark:text-emerald-400' : 
                                       (str_starts_with($perf->grade, 'D') ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400') }}">
                                    {{ $perf->grade }}
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-500">{{ $perf->supervisor->name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400 font-medium bg-slate-50 dark:bg-slate-900">
                                    Belum ada data penilaian KPI.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($performances->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $performances->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>