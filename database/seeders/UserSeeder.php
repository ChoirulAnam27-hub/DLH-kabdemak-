<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder UserSeeder — Membuat akun Admin dan Petugas default.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator DLH',
            'email' => 'admin@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08123456789',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.petugas@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '08234567890',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti.petugas@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '08345678901',
            'is_active' => true,
        ]);
    }
}
