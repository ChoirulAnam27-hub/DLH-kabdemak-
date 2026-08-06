<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Model ReportPhoto — Foto Bukti Pengaduan & Penyelesaian
 * 
 * Tipe foto:
 * - 'bukti': Foto dari pelapor sebagai bukti masalah
 * - 'penyelesaian': Foto dari petugas sebagai bukti sudah ditangani
 */
class ReportPhoto extends Model
{
    protected $fillable = [
        'report_id',
        'photo_path',
        'type',
        'caption',
    ];

    // =========================================
    // RELASI
    // =========================================

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * URL publik untuk menampilkan foto
     */
    public function getPhotoUrlAttribute(): string
    {
        return Storage::url($this->photo_path);
    }
}
