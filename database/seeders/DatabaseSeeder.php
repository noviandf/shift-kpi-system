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
            'name' => 'Supervisor',
            'email' => 'spv@kantor.com',
            'password' => Hash::make('password123'),
            'role' => 'supervisor',
        ]);

        // 2. Akun Agen
        User::factory()->create([
            'name' => 'Agen Novian',
            'email' => 'novian@kantor.com',
            'password' => Hash::make('password123'),
            'role' => 'agent',
        ]);

        // 2. Akun Agen Operasional
        User::factory(20)->create([
            'role' => 'agent',
            'password' => Hash::make('password123'),
        ]);
    }
}
