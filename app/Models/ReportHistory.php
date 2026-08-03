<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ReportHistory — Riwayat Perubahan Status Laporan.
 *
 * Mencatat setiap kali status laporan berubah sebagai audit trail,
 * termasuk siapa yang mengubah dan catatan tambahan.
 */
class ReportHistory extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'old_status',
        'new_status',
        'note',
    ];

    /**
     * Relasi ke laporan.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi ke user yang mengubah status.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Label status baru dalam Bahasa Indonesia.
     */
    public function getNewStatusLabelAttribute(): string
    {
        return match ($this->new_status) {
            'pending' => 'Menunggu',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => $this->new_status,
        };
    }
}
