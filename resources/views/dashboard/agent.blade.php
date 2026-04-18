<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Agen Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Halo Agen {{ Auth::user()->name }}! Di sini nanti Anda hanya bisa melihat Jadwal dan Rapor Kinerja.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>