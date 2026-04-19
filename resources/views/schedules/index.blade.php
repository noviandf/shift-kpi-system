<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Manajemen Jadwal Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div
                class="p-4 mb-4 text-sm font-medium text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200 shadow-sm">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="p-4 mb-4 text-sm font-medium text-red-800 rounded-lg bg-red-50 border border-red-200 shadow-sm">
                {{ session('error') }}
            </div>
            @endif

            @if(Auth::user()->role === 'supervisor')
            <div class="p-6 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <header class="mb-4">
                    <h2 class="text-lg font-bold text-slate-900 uppercase tracking-tight">Pusat Kendali Jadwal</h2>
                    <p class="mt-1 text-sm text-slate-500">Pilih Agen dan Tanggal, lalu pilih shift untuk update atau
                        "Hapus" untuk mengosongkan jadwal.</p>
                </header>

                <form method="POST" action="{{ route('schedules.store') }}" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    <div class="w-full md:w-64">
                        <x-input-label for="user_id" value="Pilih Agen" class="font-bold" />
                        <select name="user_id" id="user_id" required
                            class="w-full mt-1 border-slate-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="" disabled selected>-- Pilih Agen --</option>
                            @foreach($allAgents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <x-input-label for="shift_date" value="Tanggal Shift" class="font-bold" />
                        <input type="date" name="shift_date" id="shift_date" required
                            class="w-full mt-1 border-slate-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="w-full md:w-56">
                        <x-input-label for="shift_type" value="Kode Shift / Aksi" class="font-bold" />
                        <select name="shift_type" id="shift_type" required
                            class="w-full mt-1 border-slate-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <optgroup label="Input Shift">
                                <option value="P">P (07:00 - 16:00)</option>
                                <option value="P3">P3 (10:00 - 19:00)</option>
                                <option value="MD">MD (13:00 - 22:00)</option>
                                <option value="ML">ML (22:00 - 07:00)</option>
                                <option value="CT">CT (Cuti)</option>
                                <option value="OFF">OFF (Libur)</option>
                                <option value="I/S">I/S (Izin/Sakit)</option>
                            </optgroup>
                            <optgroup label="Aksi">
                                <option value="DELETE" class="text-red-600 font-bold bg-red-50">❌ HAPUS JADWAL</option>
                            </optgroup>
                        </select>
                    </div>

                    <button type="submit"
                        class="px-6 py-2 h-[42px] bg-slate-800 text-white font-bold rounded-md shadow-sm hover:bg-slate-700 transition uppercase tracking-widest text-xs">
                        Eksekusi
                    </button>
                </form>
            </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-slate-200 overflow-hidden">
                <header
                    class="p-4 border-b border-slate-200 flex flex-col lg:flex-row justify-between items-center bg-slate-50 gap-4">
                    <div class="flex flex-col">
                        <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                            Periode: {{ $targetDate->translatedFormat('F Y') }}
                        </h2>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Matrix Management View
                        </p>
                    </div>

                    <form method="GET" action="{{ route('schedules.index') }}"
                        class="flex flex-wrap items-center gap-2 w-full lg:w-auto justify-end">

                        <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}"
                            class="h-9 border-slate-300 rounded-md text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500 w-full md:w-32">

                        <select name="month" class="h-9 border-slate-300 rounded-md text-xs shadow-sm pr-8">
                            @for($m = 1; $m <= 12; $m++) <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                                @endfor
                        </select>

                        <select name="year" class="h-9 border-slate-300 rounded-md text-xs shadow-sm pr-8">
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++) <option value="{{ $y }}"
                                {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                        </select>

                        <button type="submit"
                            class="h-9 px-4 bg-slate-800 text-white rounded-md text-[10px] font-black uppercase hover:bg-slate-700 transition">
                            Filter
                        </button>

                        <button type="submit" name="export" value="csv"
                            class="h-9 px-3 bg-emerald-600 text-white rounded-md text-[10px] font-black uppercase hover:bg-emerald-700 transition flex items-center gap-2 shadow-sm">
                            CSV
                        </button>
                    </form>
                </header>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-left border-collapse min-w-max">
                        <thead class="bg-slate-800 text-white text-[10px] uppercase tracking-widest">
                            <tr>
                                <th
                                    class="px-4 py-4 sticky left-0 z-20 bg-slate-900 border-r border-slate-700 shadow-md">
                                    Nama Agen</th>
                                @for($day = 1; $day <= $daysInMonth; $day++) <th
                                    class="px-2 py-4 text-center border-r border-slate-700 min-w-[45px]">
                                    {{ $day }}
                                    </th>
                                    @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($agents as $agent)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td
                                    class="px-4 py-3 font-bold text-slate-900 sticky left-0 z-10 bg-white border-r border-slate-200 shadow-[2px_0_5px_rgba(0,0,0,0.05)]">
                                    {{ $agent->name }}
                                </td>

                                @for($day = 1; $day <= $daysInMonth; $day++) @php $m=str_pad($month, 2, '0' ,
                                    STR_PAD_LEFT); $d=str_pad($day, 2, '0' , STR_PAD_LEFT); $currentDateString=$year
                                    . '-' . $m . '-' . $d; $shift=$mappedSchedules[$agent->id][$currentDateString] ??
                                    null;

                                    $badgeClass = 'bg-slate-50 text-slate-300';
                                    if ($shift) {
                                    if (in_array($shift, ['P', 'P3'])) $badgeClass = 'bg-blue-100 text-blue-800
                                    border-blue-200';
                                    elseif ($shift == 'MD') $badgeClass = 'bg-amber-100 text-amber-800
                                    border-amber-200';
                                    elseif ($shift == 'ML') $badgeClass = 'bg-slate-800 text-white border-slate-900';
                                    elseif (in_array($shift, ['CT', 'OFF'])) $badgeClass = 'bg-red-100 text-red-800
                                    border-red-200';
                                    elseif ($shift == 'I/S') $badgeClass = 'bg-purple-100 text-purple-800
                                    border-purple-200';
                                    }
                                    @endphp
                                    <td class="p-1 text-center border-r border-slate-100">
                                        @if($shift)
                                        <div
                                            class="mx-auto w-9 h-9 flex items-center justify-center rounded border text-[10px] font-black {{ $badgeClass }}">
                                            {{ $shift }}
                                        </div>
                                        @else
                                        <span class="text-slate-200 text-xs">-</span>
                                        @endif
                                    </td>
                                    @endfor
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $daysInMonth + 1 }}"
                                    class="p-12 text-center text-slate-500 font-medium italic bg-slate-50">
                                    Data tidak ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-4 bg-white border border-slate-200 rounded-lg shadow-sm">
                <h3 class="text-xs font-bold text-slate-500 uppercase mb-3 tracking-widest">Keterangan Kode Shift:</h3>
                <div class="flex flex-wrap gap-4 text-[11px] font-bold">
                    <span class="flex items-center"><span
                            class="w-3 h-3 bg-blue-100 border border-blue-200 rounded mr-2"></span> P: 07:00 -
                        16:00</span>
                    <span class="flex items-center"><span
                            class="w-3 h-3 bg-blue-100 border border-blue-200 rounded mr-2"></span> P3: 10:00 -
                        19:00</span>
                    <span class="flex items-center"><span
                            class="w-3 h-3 bg-amber-100 border border-amber-200 rounded mr-2"></span> MD: 13:00 -
                        22:00</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-slate-800 rounded mr-2"></span> ML:
                        22:00 - 07:00</span>
                    <span class="flex items-center"><span
                            class="w-3 h-3 bg-red-100 border border-red-200 rounded mr-2"></span> CT / OFF: Cuti /
                        Libur</span>
                    <span class="flex items-center"><span
                            class="w-3 h-3 bg-purple-100 border border-purple-200 rounded mr-2"></span> I/S: Izin /
                        Sakit</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>