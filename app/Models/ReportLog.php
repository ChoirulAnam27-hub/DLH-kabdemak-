<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ReportLog — Audit Trail Perubahan Laporan
 * 
 * Mencatat setiap aksi pada laporan:
 * - created: Laporan baru dibuat
 * - status_changed: Status berubah
 * - assigned: Ditugaskan ke petugas
 * - resolved: Selesai ditangani
 * - note_added: Catatan ditambahkan
 */
class ReportLog extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'action',
        'description',
        'old_status',
        'new_status',
    ];

    // =========================================
    // RELASI
    // =========================================

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * User yang melakukan aksi (null jika aksi otomatis/sistem)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Label aksi yang ramah ditampilkan
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Laporan Dibuat',
            'status_changed' => 'Status Diubah',
            'assigned' => 'Ditugaskan ke Petugas',
            'resolved' => 'Selesai Ditangani',
            'rejected' => 'Laporan Ditolak',
            'note_added' => 'Catatan Ditambahkan',
            'photo_added' => 'Foto Ditambahkan',
            default => $this->action,
        };
    }

    /**
     * Icon Bootstrap untuk setiap jenis aksi
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created' => 'bi-plus-circle text-primary',
            'status_changed' => 'bi-arrow-repeat text-info',
            'assigned' => 'bi-person-check text-warning',
            'resolved' => 'bi-check-circle text-success',
            'rejected' => 'bi-x-circle text-danger',
            'note_added' => 'bi-chat-dots text-secondary',
            'photo_added' => 'bi-camera text-info',
            default => 'bi-circle text-muted',
        };
    }
}
