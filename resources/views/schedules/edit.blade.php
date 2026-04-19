<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Edit Jadwal Agen') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-200">
                <div class="p-8 text-slate-900">
                    <form method="post" action="{{ route('schedules.update', $schedule->id) }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <x-input-label value="Nama Agen" class="font-bold text-slate-700" />
                            <x-text-input class="mt-1 block w-full bg-slate-100" value="{{ $schedule->user->name }}"
                                disabled />
                            <input type="hidden" name="user_id" value="{{ $schedule->user_id }}">
                        </div>

                        <div>
                            <x-input-label for="shift_date" value="Tanggal Shift" class="font-bold text-slate-700" />
                            <x-text-input id="shift_date" name="shift_date" type="date" class="mt-1 block w-full"
                                :value="old('shift_date', $schedule->shift_date)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('shift_date')" />
                        </div>

                        <div>
                            <x-input-label for="shift_type" value="Pilih Kode Shift" class="font-bold text-slate-700" />
                            <select name="shift_type" id="shift_type"
                                class="border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full mt-1"
                                required>
                                <option value="P" {{ $schedule->shift_type == 'P' ? 'selected' : '' }}>P (Pagi)</option>
                                <option value="P3" {{ $schedule->shift_type == 'P3' ? 'selected' : '' }}>P3 (Pagi 3)
                                </option>
                                <option value="MD" {{ $schedule->shift_type == 'MD' ? 'selected' : '' }}>MD (Middle)
                                </option>
                                <option value="ML" {{ $schedule->shift_type == 'ML' ? 'selected' : '' }}>ML (Malam)
                                </option>
                                <option value="CT" {{ $schedule->shift_type == 'CT' ? 'selected' : '' }}>CT (Cuti)
                                </option>
                                <option value="OFF" {{ $schedule->shift_type == 'OFF' ? 'selected' : '' }}>OFF (Libur)
                                </option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('shift_type')" />
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                            <x-primary-button class="bg-blue-600 hover:bg-blue-700">Simpan Perubahan</x-primary-button>
                            <a href="{{ route('schedules.index') }}"
                                class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>