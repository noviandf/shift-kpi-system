<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Performance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'supervisor') {
            // 1. Total Agen (Aktif)
            $totalAgents = User::where('role', 'agent')->count();

            // 2. Shift Hari Ini (Hanya menghitung jadwal yang tanggalnya SAMA dengan hari ini)
            $shiftsToday = Schedule::whereDate('shift_date', today())->count();

            // 3. Rata-rata KPI (Mengambil rata-rata dari kolom final_score, default 0 jika kosong)
            $avgKpi = Performance::avg('final_score') ?? 0;
            $avgKpi = number_format($avgKpi, 1); // Format 1 angka di belakang koma

            // 4. Perubahan Jadwal Terbaru (Ambil 5 jadwal yang paling terakhir diinput/diupdate)
            $recentSchedules = Schedule::with('user')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            // Pastikan nama variabel di compact() SESUAI dengan yang ada di supervisor.blade.php
            return view('dashboard.supervisor', compact('totalAgents', 'shiftsToday', 'avgKpi', 'recentSchedules'));
        } elseif ($user->role === 'agent') {
            // Logika Agen: Hanya lihat data miliknya sendiri

            // Ambil jadwal mulai hari ini dan ke depannya
            $upcomingShifts = Schedule::where('user_id', $user->id) // Ubah nama variabel jadi upcomingShifts agar sesuai UI
                ->where('shift_date', '>=', today())
                ->orderBy('shift_date', 'asc')
                ->take(3) // Sesuai desain, cukup 3 terdekat
                ->get();

            // Ambil Rapor KPI TERAKHIR milik agen ini (hanya butuh 1 rapor terbaru untuk dashboard)
            $latestKpi = Performance::where('user_id', $user->id) // Ubah nama variabel jadi latestKpi
                ->orderBy('evaluation_month', 'desc')
                ->first();

            return view('dashboard.agent', compact('upcomingShifts', 'latestKpi'));
        }

        return abort(403, 'Anda tidak memiliki hak akses.');
    }
}
