<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Category — Kategori Jenis Pengaduan
 * 
 * Contoh: Sampah Menumpuk, Pencemaran Air, Pencemaran Udara, dll.
 * Setiap kategori memiliki icon dan warna untuk tampilan di peta.
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // =========================================
    // RELASI
    // =========================================

    /**
     * Semua laporan dalam kategori ini
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // =========================================
    // SCOPE
    // =========================================

    /**
     * Hanya kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
