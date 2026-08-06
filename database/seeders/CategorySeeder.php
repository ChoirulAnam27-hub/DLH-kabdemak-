<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeder: Kategori Pengaduan Lingkungan
 * 
 * 5 kategori utama pengaduan di DLH Kabupaten Demak.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sampah Menumpuk',
                'slug' => 'sampah-menumpuk',
                'icon' => 'bi-trash3-fill',
                'color' => '#dc3545',
                'description' => 'Penumpukan sampah di TPS, jalan, sungai, atau area publik',
            ],
            [
                'name' => 'Pencemaran Air',
                'slug' => 'pencemaran-air',
                'icon' => 'bi-droplet-fill',
                'color' => '#0d6efd',
                'description' => 'Pencemaran sungai, sumur, atau sumber air lainnya',
            ],
            [
                'name' => 'Pencemaran Udara',
                'slug' => 'pencemaran-udara',
                'icon' => 'bi-cloud-haze2-fill',
                'color' => '#6f42c1',
                'description' => 'Asap pabrik, pembakaran sampah, bau menyengat',
            ],
            [
                'name' => 'Limbah B3',
                'slug' => 'limbah-b3',
                'icon' => 'bi-radioactive',
                'color' => '#fd7e14',
                'description' => 'Pembuangan limbah berbahaya dan beracun secara ilegal',
            ],
            [
                'name' => 'Lainnya',
                'slug' => 'lainnya',
                'icon' => 'bi-exclamation-triangle-fill',
                'color' => '#6c757d',
                'description' => 'Pengaduan lingkungan lainnya yang tidak tercakup kategori di atas',
            ],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
