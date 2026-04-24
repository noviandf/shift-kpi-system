<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Dashboard Supervisor') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-slate-800 dark:bg-slate-900 rounded-lg p-6 shadow-md border border-slate-900 dark:border-slate-800 text-white flex justify-between items-center transition-colors">
                <div>
                    <h3 class="text-2xl font-bold">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-slate-300 dark:text-slate-400 mt-1 text-sm">Berikut adalah ringkasan operasional tim Anda hari ini.</p>
                </div>
                <div class="hidden md:block">
                    <span class="px-4 py-2 bg-slate-700 dark:bg-slate-800 rounded-md text-sm font-bold shadow-inner text-slate-200">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Agen</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ $totalAgents ?? '0' }} <span class="text-sm font-medium text-slate-500 dark:text-slate-500">Aktif</span></p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 flex items-center space-x-4">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Shift Hari Ini</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ $shiftsToday ?? '0' }} <span class="text-sm font-medium text-slate-500 dark:text-slate-500">Jadwal</span></p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 flex items-center space-x-4">
                    <div class="p-3 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rata-rata KPI</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ $avgKpi ?? '0' }} <span class="text-sm font-medium text-slate-500 dark:text-slate-500">/ 100</span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Perubahan Jadwal Terbaru</h3>
                        <a href="{{ route('schedules.index') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">Kelola Semua &rarr;</a>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-400">
                            <thead class="text-xs text-slate-700 dark:text-slate-300 uppercase bg-slate-50 dark:bg-slate-800/50">
                                <tr>
                                    <th class="px-6 py-3 border-b dark:border-slate-800">Agen</th>
                                    <th class="px-6 py-3 border-b dark:border-slate-800">Tgl Shift</th>
                                    <th class="px-6 py-3 border-b dark:border-slate-800">Jenis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @if(isset($recentSchedules) && count($recentSchedules) > 0)
                                @foreach($recentSchedules as $schedule)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-3 font-medium text-slate-900 dark:text-slate-200">{{ $schedule->user->name }}</td>
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($schedule->shift_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-1 text-xs font-bold rounded-md bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            {{ $schedule->shift_type }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-slate-500 dark:text-slate-500 italic">Belum ada data jadwal terbaru.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('schedules.index') }}" class="flex items-center p-3 text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <div class="bg-white dark:bg-slate-700 p-2 rounded shadow-sm mr-3">📅</div>
                            <span class="font-bold text-sm">Kelola Jadwal</span>
                        </a>
                        <a href="{{ route('performances.index') }}" class="flex items-center p-3 text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <div class="bg-white dark:bg-slate-700 p-2 rounded shadow-sm mr-3">📈</div>
                            <span class="font-bold text-sm">Evaluasi KPI Agen</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>