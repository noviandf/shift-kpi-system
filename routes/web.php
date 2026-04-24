<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PerformanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Middleware Utama (Hanya untuk user yang sudah Login)
Route::middleware(['auth', 'verified'])->group(function () {

    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // =========================================================================
    // RUTE BERSAMA (READ-ONLY)
    // Agen BISA mengakses halaman ini karena ada di luar grup supervisor
    // =========================================================================
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/performances', [PerformanceController::class, 'index'])->name('performances.index');


    // =========================================================================
    // RUTE KHUSUS SUPERVISOR (MODIFIKASI DATA)
    // Agen akan DITOLAK (Error 403) jika mencoba mengeksekusi rute di bawah ini
    // =========================================================================
    Route::middleware('role:supervisor')->group(function () {

        // Modifikasi Jadwal
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        // Jalur untuk Simpan/Edit Manual
        Route::post('/schedules/store', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::resource('users', UserController::class);

        // Jalur untuk Import CSV Masal
        Route::post('/schedules/import', [ScheduleController::class, 'import'])->name('schedule.import');
        Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Modifikasi KPI
        Route::post('/performances', [PerformanceController::class, 'store'])->name('performances.store');
    });
});

require __DIR__ . '/auth.php';
