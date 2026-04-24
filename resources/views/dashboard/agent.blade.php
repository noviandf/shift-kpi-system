<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Dashboard Agen Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-900 rounded-lg p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <div class="w-12 h-12 rounded-full bg-slate-800 dark:bg-slate-700 text-white flex items-center justify-center text-xl font-bold shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Halo, {{ Auth::user()->name }}!</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Role: <span class="font-bold text-blue-600 dark:text-blue-400">Agent</span></p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase">Waktu Sistem</p>
                    <p class="text-lg font-black text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Rapor KPI Terakhir</h3>
                    </div>

                    @if($latestKpi)
                    <div class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 mb-4">
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">
                            {{ $latestKpi->evaluation_month }}
                        </p>
                        <div class="text-6xl font-black text-slate-800 dark:text-slate-100 mb-3">{{ $latestKpi->final_score }}</div>
                        <div class="px-6 py-1.5 bg-slate-800 dark:bg-slate-700 text-white rounded-full font-bold text-sm tracking-widest uppercase shadow-sm">
                            Grade {{ $latestKpi->grade }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center text-sm">
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/50 rounded border border-slate-100 dark:border-slate-700">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-1">QA</p>
                            <p class="font-black text-slate-800 dark:text-slate-200">{{ $latestKpi->qa_raw }}</p>
                        </div>
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/50 rounded border border-slate-100 dark:border-slate-700">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-1">Hadir</p>
                            <p class="font-black text-slate-800 dark:text-slate-200">{{ $latestKpi->attendance_raw }}%</p>
                        </div>
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/50 rounded border border-slate-100 dark:border-slate-700">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-1">AHT</p>
                            <p class="font-black text-slate-800 dark:text-slate-200">{{ $latestKpi->aht_raw }}</p>
                        </div>
                    </div>
                    @else
                    <div class="h-48 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800/30">
                        <svg class="w-10 h-10 mb-2 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="font-bold text-sm">Belum ada rapor KPI.</p>
                    </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Jadwal Shift Mendatang</h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($upcomingShifts as $index => $shift)
                        <div class="flex items-center justify-between p-4 rounded-lg border transition-colors 
                                    {{ $index === 0 ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 shadow-sm' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700' }}">
                            <div class="flex items-center">
                                <div class="w-16 text-center mr-4 border-r pr-4 
                                            {{ $index === 0 ? 'border-blue-200 dark:border-blue-800' : 'border-slate-200 dark:border-slate-700' }}">
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->translatedFormat('M') }}
                                    </p>
                                    <p class="text-2xl font-black {{ $index === 0 ? 'text-blue-700 dark:text-blue-400' : 'text-slate-800 dark:text-slate-200' }}">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->format('d') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900 dark:text-slate-100">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->translatedFormat('l') }}
                                    </p>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->format('Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full border
                                    @if($shift->shift_type == 'Pagi' || str_starts_with($shift->shift_type, 'P')) 
                                        bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 border-blue-200 dark:border-blue-700
                                    @elseif($shift->shift_type == 'Middle' || str_starts_with($shift->shift_type, 'MD')) 
                                        bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 border-amber-200 dark:border-amber-700
                                    @elseif($shift->shift_type == 'Malam' || str_starts_with($shift->shift_type, 'ML')) 
                                        bg-slate-800 dark:bg-slate-700 text-white border-slate-900 dark:border-slate-600
                                    @else 
                                        bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-slate-600 
                                    @endif">
                                {{ $shift->shift_type }}
                            </span>
                        </div>
                        @empty
                        <div class="p-6 bg-slate-50 dark:bg-slate-800/30 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 text-center flex flex-col items-center justify-center h-48">
                            <svg class="w-10 h-10 mb-2 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-bold text-sm text-slate-500 dark:text-slate-400">Anda tidak memiliki jadwal shift mendatang.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>