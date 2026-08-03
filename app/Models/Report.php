<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Model Report — Laporan Pengaduan Sampah & Pencemaran.
 *
 * Fitur utama:
 * - Auto-generate kode tiket unik (DLH-YYYYMMDD-XXX)
 * - Scope filter berdasarkan status, kategori, kecamatan
 * - Relasi ke User (petugas yang ditugaskan) dan ReportHistory
 */
class Report extends Model
{
    protected $fillable = [
        'ticket_code',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'category',
        'description',
        'latitude',
        'longitude',
        'address',
        'kelurahan',
        'kecamatan',
        'photo_path',
        'status',
        'priority',
        'assigned_to',
        'resolved_photo',
        'resolved_note',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Boot method — Auto-generate kode tiket saat membuat laporan baru.
     * Format: DLH-YYYYMMDD-XXX (contoh: DLH-20260803-001)
     */
    protected static function booted()
    {
        static::creating(function ($report) {
            if (empty($report->ticket_code)) {
                $report->ticket_code = self::generateTicketCode();
            }
        });
    }

    /**
     * Generate kode tiket unik.
     * Menggunakan format DLH-YYYYMMDD-XXX dengan auto-increment harian.
     */
    public static function generateTicketCode(): string
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = "DLH-{$today}-";

        // Cari nomor urut terakhir hari ini
        $lastReport = self::where('ticket_code', 'like', $prefix . '%')
            ->orderBy('ticket_code', 'desc')
            ->first();

        if ($lastReport) {
            $lastNumber = (int) substr($lastReport->ticket_code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // ===== RELASI =====

    /**
     * Petugas yang ditugaskan menangani laporan ini.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Riwayat perubahan status laporan.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class)->orderBy('created_at', 'desc');
    }

    // ===== SCOPE FILTER =====

    /**
     * Filter berdasarkan status.
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Filter berdasarkan kategori.
     */
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Filter berdasarkan kecamatan.
     */
    public function scopeByKecamatan($query, $kecamatan)
    {
        if ($kecamatan) {
            return $query->where('kecamatan', $kecamatan);
        }
        return $query;
    }

    /**
     * Filter berdasarkan rentang tanggal.
     */
    public function scopeByDateRange($query, $from, $to)
    {
        if ($from && $to) {
            return $query->whereBetween('created_at', [$from, $to]);
        }
        return $query;
    }

    // ===== HELPER =====

    /**
     * Label status dalam Bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }

    /**
     * Warna badge status untuk UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'diproses' => 'info',
            'selesai' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Label kategori dalam Bahasa Indonesia.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'sampah' => 'Penumpukan Sampah',
            'pencemaran_air' => 'Pencemaran Air',
            'pencemaran_udara' => 'Pencemaran Udara',
            'lainnya' => 'Lainnya',
            default => $this->category,
        };
    }

    /**
     * Label prioritas.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'rendah' => 'success',
            'sedang' => 'warning',
            'tinggi' => 'orange',
            'darurat' => 'danger',
            default => 'secondary',
        };
    }
}
