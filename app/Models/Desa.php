<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Desa — Master Data Wilayah Desa/Kelurahan
 * 
 * Setiap desa berada di bawah satu kecamatan.
 * Digunakan sebagai dependent dropdown yang otomatis
 * ter-filter ketika kecamatan dipilih.
 */
class Desa extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'name',
    ];

    // =========================================
    // RELASI
    // =========================================

    /**
     * Kecamatan induk
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * Laporan pengaduan di desa ini
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
