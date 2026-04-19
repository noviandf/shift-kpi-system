<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Setup Konfigurasi Waktu
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $targetDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $targetDate->daysInMonth;

        // 2. Query Agen (Transparansi Terbuka)
        // OPINI ANDA DITERAPKAN: Semua orang kini bisa melihat semua agen.
        $agentsQuery = User::where('role', 'agent');

        // Fitur pencarian nama sekarang bisa dipakai oleh Supervisor DAN Agen
        if ($request->filled('search')) {
            $agentsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $agents = $agentsQuery->get();

        // 3. Tarik Data Jadwal (Semua Jadwal di Bulan Tersebut)
        $querySchedules = Schedule::whereMonth('shift_date', $month)
            ->whereYear('shift_date', $year);

        $schedulesData = $querySchedules->get();

        // 4. Fitur Ekspor CSV
        if ($request->has('export') && $request->export === 'csv') {
            $fileName = "Laporan_Jadwal_{$targetDate->translatedFormat('F_Y')}.csv";

            return response()->streamDownload(function () use ($schedulesData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Nama Agen', 'Tanggal', 'Kode Shift']);

                foreach ($schedulesData as $s) {
                    fputcsv($file, [$s->user->name, $s->shift_date, $s->shift_type]);
                }
                fclose($file);
            }, $fileName, [
                'Content-Type' => 'text/csv',
                'Cache-Control' => 'no-cache, must-revalidate'
            ]);
        }

        // 5. Transformasi Data Matrix
        $mappedSchedules = [];
        foreach ($schedulesData as $schedule) {
            $mappedSchedules[$schedule->user_id][$schedule->shift_date] = $schedule->shift_type;
        }

        $allAgents = User::where('role', 'agent')->get();

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
}
