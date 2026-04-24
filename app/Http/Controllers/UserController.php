<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar seluruh agen
    public function index()
    {
        $users = User::where('role', 'agent')->get();
        return view('users.index', compact('users'));
    }

    // Menampilkan form tambah agen baru
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan data agen baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Password default
            'role' => 'agent',
        ]);

        return redirect()->route('users.index')->with('success', 'Agen baru berhasil ditambahkan. Password default: password123');
    }

    // Menampilkan form edit data agen
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Memperbarui data agen
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with('success', 'Data agen berhasil diperbarui.');
    }

    // Menghapus akun agen
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun agen telah dihapus.');
    }
}
