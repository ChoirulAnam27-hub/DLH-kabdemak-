<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed seluruh database aplikasi.
     * Urutan penting: Wilayah → User → Report (karena foreign key).
     */
    public function run(): void
    {
        $this->call([
            WilayahDemakSeeder::class,
            UserSeeder::class,
            ReportSeeder::class,
        ]);
    }
}
