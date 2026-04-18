<?php

namespace App\Http\Controllers; // Pastikan ini benar

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller // Nama class harus sama dengan nama file
{
    public function index()
    {
        $agents = User::where('role', 'agent')->get();
        $schedules = Schedule::with('user')->orderBy('shift_date', 'desc')->get();
        return view('schedules.index', compact('agents', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:Pagi,Siang,Malam,Libur',
        ]);

        Schedule::create([
            'user_id' => $request->user_id,
            'shift_date' => $request->shift_date,
            'shift_type' => $request->shift_type,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
    }
}
