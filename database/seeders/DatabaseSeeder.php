<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Supervisor
        User::factory()->create([
            'name' => 'Bapak Supervisor',
            'email' => 'spv@kantor.com',
            'password' => Hash::make('password123'),
            'role' => 'supervisor',
        ]);

        // 2. Akun Agen Operasional
        User::factory()->create([
            'name' => 'Agen Novian',
            'email' => 'novian@kantor.com',
            'password' => Hash::make('password123'),
            'role' => 'agent',
        ]);
    }
}
