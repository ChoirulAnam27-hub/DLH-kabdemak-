<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder: User Admin & Petugas DLH Demak
 * 
 * Membuat 1 admin dan 3 petugas lapangan dummy.
 * Password default: 'password'
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        User::create([
            'name' => 'Admin DLH Demak',
            'email' => 'admin@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'nip' => '198501012010011001',
            'is_active' => true,
        ]);

        // Petugas Lapangan 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'nip' => '199001012015011001',
            'is_active' => true,
        ]);

        // Petugas Lapangan 2
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567892',
            'nip' => '199201012016012001',
            'is_active' => true,
        ]);

        // Petugas Lapangan 3
        User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@dlh-demak.go.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567893',
            'nip' => '198801012014011001',
            'is_active' => true,
        ]);
    }
}
