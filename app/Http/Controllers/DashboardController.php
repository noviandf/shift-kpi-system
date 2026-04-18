<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek role user yang sedang login
        $role = Auth::user()->role;

        // Arahkan ke file tampilan (view) yang berbeda sesuai role
        if ($role === 'supervisor') {
            return view('dashboard.supervisor');
        } elseif ($role === 'agent') {
            return view('dashboard.agent');
        }

        // Default jika terjadi error (meskipun kecil kemungkinannya)
        return abort(403, 'Anda tidak memiliki hak akses.');
    }
}
