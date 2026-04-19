<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Agen Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div
                class="bg-white rounded-lg p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <div
                        class="w-12 h-12 rounded-full bg-slate-800 text-white flex items-center justify-center text-xl font-bold shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Halo, {{ Auth::user()->name }}!</h3>
                        <p class="text-slate-500 text-sm">Role: <span class="font-bold text-blue-600">
                                Agent</span></p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-sm font-bold text-slate-500 uppercase">Waktu Sistem</p>
                    <p class="text-lg font-black text-slate-800">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-2">
                        <h3 class="text-lg font-bold text-slate-900">
                            Rapor KPI Terakhir
                        </h3>
                    </div>

                    @if($latestKpi)
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-lg border border-slate-200 mb-4">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">
                            {{ $latestKpi->evaluation_month }}
                        </p>
                        <div class="text-6xl font-black text-slate-800 mb-3">{{ $latestKpi->final_score }}</div>
                        <div
                            class="px-6 py-1.5 bg-slate-800 text-white rounded-full font-bold text-sm tracking-widest uppercase shadow-sm">
                            Grade {{ $latestKpi->grade }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center text-sm">
                        <div class="p-2 bg-slate-50 rounded border border-slate-100">
                            <p class="text-slate-500 text-xs font-bold uppercase mb-1">QA</p>
                            <p class="font-black text-slate-800">{{ $latestKpi->qa_raw }}</p>
                        </div>
                        <div class="p-2 bg-slate-50 rounded border border-slate-100">
                            <p class="text-slate-500 text-xs font-bold uppercase mb-1">Hadir</p>
                            <p class="font-black text-slate-800">{{ $latestKpi->attendance_raw }}%</p>
                        </div>
                        <div class="p-2 bg-slate-50 rounded border border-slate-100">
                            <p class="text-slate-500 text-xs font-bold uppercase mb-1">AHT</p>
                            <p class="font-black text-slate-800">{{ $latestKpi->aht_raw }}</p>
                        </div>
                    </div>
                    @else
                    <div
                        class="h-48 flex flex-col items-center justify-center text-slate-500 border-2 border-dashed border-slate-200 rounded-lg bg-slate-50">
                        <svg class="w-10 h-10 mb-2 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="font-bold text-sm">Belum ada rapor KPI.</p>
                    </div>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-2">
                        <h3 class="text-lg font-bold text-slate-900">
                            Jadwal Shift Mendatang
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($upcomingShifts as $index => $shift)
                        <div
                            class="flex items-center justify-between p-4 rounded-lg border {{ $index === 0 ? 'bg-blue-50 border-blue-200 shadow-sm' : 'bg-white border-slate-200' }}">
                            <div class="flex items-center">
                                <div
                                    class="w-16 text-center mr-4 border-r {{ $index === 0 ? 'border-blue-200' : 'border-slate-200' }} pr-4">
                                    <p class="text-xs font-bold text-slate-500 uppercase">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->translatedFormat('M') }}
                                    </p>
                                    <p
                                        class="text-2xl font-black {{ $index === 0 ? 'text-blue-700' : 'text-slate-800' }}">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->format('d') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->translatedFormat('l') }}
                                    </p>
                                    <p class="text-xs font-medium text-slate-500">
                                        {{ \Carbon\Carbon::parse($shift->shift_date)->format('Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full border
                                    @if($shift->shift_type == 'Pagi') bg-blue-100 text-blue-800 border-blue-200
                                    @elseif($shift->shift_type == 'Siang') bg-amber-100 text-amber-800 border-amber-200
                                    @elseif($shift->shift_type == 'Malam') bg-slate-800 text-white border-slate-900
                                    @else bg-slate-100 text-slate-600 border-slate-300 @endif">
                                {{ $shift->shift_type }}
                            </span>
                        </div>
                        @empty
                        <div
                            class="p-6 bg-slate-50 rounded-lg border-2 border-dashed border-slate-200 text-center flex flex-col items-center justify-center h-48">
                            <svg class="w-10 h-10 mb-2 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="font-bold text-sm text-slate-500">Anda tidak memiliki jadwal shift mendatang.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>