<?php

namespace App\Http\Controllers;

use App\Models\Performance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Performance::with(['user', 'supervisor']);

        // 1. Logika Isolasi Data
        if (Auth::user()->role === 'agent') {
            $query->where('user_id', Auth::id());
        } else if (Auth::user()->role === 'supervisor') {
            // Pencarian nama khusus Supervisor
            if ($request->filled('search')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }
        }

        // 2. Logika Filter Bulan (Berlaku untuk semua Role)
        if ($request->filled('filter_month')) {
            $query->where('evaluation_month', $request->filter_month);
        }

        // 3. Logika Export CSV (Otomatis mengikuti filter yang aktif)
        if ($request->has('export') && $request->export == 'csv') {
            $performances = $query->orderBy('evaluation_month', 'desc')->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=laporan_kpi_agen.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function () use ($performances) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Bulan Evaluasi', 'Nama Agen', 'Skor QA', 'Kehadiran (%)', 'Skor AHT', 'Skor Akhir', 'Grade', 'Dinilai Oleh']);

                foreach ($performances as $row) {
                    fputcsv($file, [
                        $row->evaluation_month,
                        $row->user->name,
                        $row->qa_raw,
                        $row->attendance_raw,
                        $row->aht_raw,
                        $row->final_score,
                        $row->grade,
                        $row->supervisor->name ?? '-'
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $performances = $query->orderBy('evaluation_month', 'desc')->paginate(10);
        $agents = User::where('role', 'agent')->get();

        return view('performances.index', compact('performances', 'agents'));
    }
    public function store(Request $request)
    {
        // 1. Validasi Inputan
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'evaluation_month' => 'required|string|max:50',
            'aht_raw' => 'required|numeric',
            'qa_raw' => 'required|numeric|min:0|max:100',
            'attendance_raw' => 'required|numeric|min:0|max:100',
        ]);

        // 2. Kalkulasi Logika Bisnis (Bisa Anda sesuaikan nanti formulanya)
        // Contoh asumsi bobot: QA (40%), Kehadiran (30%), AHT (30% - butuh konversi jika AHT berupa detik)
        // Untuk MVP ini, mari kita asumsikan AHT sudah berupa skor 0-100 agar rasional dijumlahkan.

        $finalScore = ($request->qa_raw * 0.40) + ($request->attendance_raw * 0.30) + ($request->aht_raw * 0.30);

        // 3. Tentukan Grade Kritis
        if ($finalScore >= 90) {
            $grade = 'A (Sangat Baik)';
        } elseif ($finalScore >= 80) {
            $grade = 'B (Baik)';
        } elseif ($finalScore >= 70) {
            $grade = 'C (Cukup)';
        } else {
            $grade = 'D (Kurang)';
        }

        // 4. Simpan ke Database
        Performance::create([
            'user_id' => $request->user_id,
            'supervisor_id' => Auth::id(), // Otomatis mencatat siapa SPV yang login
            'evaluation_month' => $request->evaluation_month,
            'aht_raw' => $request->aht_raw,
            'qa_raw' => $request->qa_raw,
            'attendance_raw' => $request->attendance_raw,
            'final_score' => $finalScore,
            'grade' => $grade,
        ]);

        return redirect()->back()->with('success', 'Data performa KPI berhasil dihitung dan disimpan!');
    }
}
