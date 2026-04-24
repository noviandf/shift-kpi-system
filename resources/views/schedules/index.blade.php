<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Manajemen Jadwal Operasional') }}
            </h2>
            <button onclick="toggleModal('modalImport')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm transition">
                📥 Import CSV Masal
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <ul class="list-disc ml-5 text-sm font-bold">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-sm font-bold text-gray-500 uppercase mb-4 tracking-wider">Pusat Kendali Manual (Revisi Harian)</h3>
                <form action="{{ route('schedule.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Agen</label>
                        <select name="user_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($allAgents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="shift_date" required class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kode Shift</label>
                        <select name="shift_type" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="P">P (Pagi)</option>
                            <option value="P3">P3 (Pagi 3)</option>
                            <option value="MD">MD (Middle)</option>
                            <option value="ML">ML (Malam)</option>
                            <option value="OFF">OFF (Libur)</option>
                            <option value="CT">CT (Cuti)</option>
                            <option value="I/S">I/S (Izin/Sakit)</option>
                            <option value="DELETE">❌ HAPUS JADWAL</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition">
                        Simpan / Update
                    </button>
                </form>
            </div>


            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('schedules.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Cari Nama Agen</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama..." class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 w-48">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Bulan</label>
                        <select name="month" class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                        <input type="number" name="year" value="{{ $year }}" class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 w-24">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-md shadow-sm text-sm transition">
                            🔍 Filter Data
                        </button>
                        <button type="submit" name="export" value="csv" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md shadow-sm text-sm transition">
                            📄 Export CSV
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex flex-wrap gap-4 text-[11px] font-bold text-gray-600">
                    <span class="flex items-center"><span class="w-3 h-3 bg-blue-100 border border-blue-200 rounded mr-2"></span> PAGI (P/P3)</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-amber-100 border border-amber-200 rounded mr-2"></span> MIDDLE (MD)</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-slate-800 rounded mr-2"></span> MALAM (ML)</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-red-100 border border-red-200 rounded mr-2"></span> LIBUR/CUTI/IZIN (OFF/CT/I/S)</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto relative">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 sticky left-0 bg-gray-100 z-10 border-r shadow-md">Nama Agen</th>
                                @for($i = 1; $i <= $daysInMonth; $i++)
                                    <th class="px-2 py-3 text-center border-r min-w-[45px]">{{ $i }}</th>
                                    @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agents as $agent)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white z-10 border-r shadow-md">
                                    {{ $agent->name }}
                                </td>
                                @for($i = 1; $i <= $daysInMonth; $i++)
                                    @php
                                    // Generate string tanggal YYYY-MM-DD
                                    $currDate=\Carbon\Carbon::createFromDate($year, $month, $i)->format('Y-m-d');

                                    // Pencarian Array Cepat (Efisiensi tinggi, tidak membebani database)
                                    $shiftCode = $mappedSchedules[$agent->id][$currDate] ?? null;
                                    @endphp
                                    <td class="px-1 py-2 text-center border-r">
                                        @if($shiftCode)
                                        @php
                                        $color = match($shiftCode) {
                                        'P', 'P3' => 'bg-blue-100 text-blue-800',
                                        'MD' => 'bg-amber-100 text-amber-800',
                                        'ML' => 'bg-slate-800 text-white',
                                        'OFF', 'CT', 'I/S' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100'
                                        };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $color }}">
                                            {{ $shiftCode }}
                                        </span>
                                        @else
                                        <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                    @endfor
                            </tr>
                            @empty
                            <tr>
                                <td colspan="32" class="p-4 text-center">Data agen tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalImport" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="toggleModal('modalImport')"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Import Jadwal Masal (.csv)</h3>
                <form action="{{ route('schedule.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold mb-1">Bulan</label>
                        <select name="bulan" class="w-full border-gray-300 rounded-md shadow-sm">
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1">Tahun</label>
                        <input type="number" name="tahun" value="{{ date('Y') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1">File CSV</label>
                        <input type="file" name="file_csv" accept=".csv" required class="w-full text-sm">
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="toggleModal('modalImport')" class="text-gray-500 hover:text-gray-700 font-bold text-sm">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold text-sm shadow">Proses Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }
    </script>
</x-app-layout>