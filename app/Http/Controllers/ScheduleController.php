<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Imports\ScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {
        // 1. Setup Konfigurasi Waktu (Tangkap dari form filter, default ke waktu saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $targetDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $targetDate->daysInMonth;

        // 2. Tarik Semua Agen (Dibutuhkan untuk Dropdown Form Input Manual)
        $allAgents = User::where('role', 'agent')->get();

        // 3. Tarik Agen untuk Tabel Matriks (Dilengkapi Fitur Filter Pencarian Nama)
        $agentsQuery = User::where('role', 'agent');
        if ($request->filled('search')) {
            $agentsQuery->where('name', 'like', '%' . $request->search . '%');
        }
        $agents = $agentsQuery->get();

        // 4. Tarik Seluruh Jadwal Operasional di Bulan Tersebut
        $schedulesData = Schedule::whereMonth('shift_date', $month)
            ->whereYear('shift_date', $year)
            ->get();

        // 5. Fitur Ekspor CSV
        if ($request->has('export') && $request->export === 'csv') {
            $fileName = "Laporan_Jadwal_{$targetDate->translatedFormat('F_Y')}.csv";

            return response()->streamDownload(function () use ($schedulesData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Nama Agen', 'Tanggal', 'Kode Shift']);

                foreach ($schedulesData as $s) {
                    fputcsv($file, [$s->user->name ?? 'Unknown', $s->shift_date, $s->shift_type]);
                }
                fclose($file);
            }, $fileName, [
                'Content-Type' => 'text/csv',
                'Cache-Control' => 'no-cache, must-revalidate'
            ]);
        }

        // 6. Transformasi Data Matrix (Metode Array Lookup untuk performa super cepat)
        $mappedSchedules = [];
        foreach ($schedulesData as $schedule) {
            $mappedSchedules[$schedule->user_id][$schedule->shift_date] = $schedule->shift_type;
        }

        // 7. Lempar semua variabel yang sudah disinkronkan ke index.blade.php
        return view('schedules.index', compact(
            'agents',
            'mappedSchedules',
            'daysInMonth',
            'month',
            'year',
            'targetDate',
            'allAgents'
        ));
    }
    public function store(Request $request)
    {
        // Validasi diperbarui: Ditambah opsi I/S
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:P,P3,MD,ML,CT,OFF,I/S,DELETE',
        ], [
            'shift_type.in' => 'Kode shift tidak valid. Gunakan P, P3, MD, ML, CT, OFF, atau I/S.',
        ]);

        // Logika Khusus Hapus (Kosongkan Sel)
        if ($request->shift_type === 'DELETE') {
            $deleted = Schedule::where('user_id', $request->user_id)
                ->where('shift_date', $request->shift_date)
                ->delete();

            if ($deleted) {
                return back()->with('success', 'Jadwal berhasil dikosongkan.');
            }
            return back()->with('error', 'Tidak ada jadwal di tanggal tersebut untuk dihapus.');
        }

        // Logika Input/Edit Hybrid
        Schedule::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'shift_date' => $request->shift_date
            ],
            [
                'shift_type' => $request->shift_type
            ]
        );

        return back()->with('success', 'Jadwal berhasil disimpan/diperbarui.');
    }

    public function edit(Schedule $schedule)
    {
        $agents = User::where('role', 'agent')->get();
        return view('schedules.edit', compact('schedule', 'agents'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:P,P3,MD,ML,CT,OFF,I/S',
        ]);

        $schedule->update([
            'shift_date' => $request->shift_date,
            'shift_type' => $request->shift_type,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Perubahan jadwal berhasil disimpan.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function import(Request $request)
    {
        // 1. Validasi ketat untuk mencegah user mengunggah file sembarangan
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt,xlsx|max:2048',
            'bulan'    => 'required|integer|min:1|max:12',
            'tahun'    => 'required|integer|min:2024',
        ]);

        try {
            // 2. Eksekusi proses import menggunakan class manual yang baru saja dibuat
            Excel::import(new ScheduleImport($request->bulan, $request->tahun), $request->file('file_csv'));

            // 3. Kembalikan user ke halaman dengan pesan sukses
            return back()->with('success', 'Ratusan data jadwal berhasil ditarik ke database dalam hitungan detik!');
        } catch (\Exception $e) {
            // 4. Tangkap error jika format CSV berantakan agar web tidak crash (layar putih)
            return back()->with('error', 'Gagal memproses CSV: ' . $e->getMessage());
        }
    }
}
