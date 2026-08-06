<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Kecamatan — Master Data Wilayah Kecamatan
 * 
 * 14 Kecamatan di Kabupaten Demak.
 * Digunakan untuk dependent dropdown pada form pelaporan
 * dan filter wilayah di dashboard admin.
 */
class Kecamatan extends Model
{
    protected $fillable = [
        'name',
    ];

    // =========================================
    // RELASI
    // =========================================

    /**
     * Desa-desa dalam kecamatan ini
     */
    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class)->orderBy('name');
    }

    /**
     * Laporan pengaduan di kecamatan ini
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
