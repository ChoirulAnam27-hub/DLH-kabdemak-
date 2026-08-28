<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Report — Laporan Pengaduan Utama
 * 
 * Model inti sistem. Menyimpan semua data pengaduan dari warga
 * termasuk lokasi GPS, status penanganan, dan disposisi petugas.
 * 
 * Status flow: pending → diproses → selesai/ditolak
 */
class Report extends Model
{
    protected $fillable = [
        'ticket_code',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'is_anonymous',
        'category_id',
        'description',
        'waste_type',
        'latitude',
        'longitude',
        'address',
        'kelurahan',
        'kecamatan',
        'kecamatan_id',
        'desa_id',
        'status',
        'priority',
        'assigned_to',
        'assigned_at',
        'admin_notes',
        'resolution_notes',
        'rejection_reason',
        'resolved_at',
        'sla_due_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'assigned_at' => 'datetime',
            'resolved_at' => 'datetime',
            'sla_due_at' => 'datetime',
            'is_anonymous' => 'boolean',
        ];
    }

    // =========================================
    // RELASI
    // =========================================

    /**
     * Kategori pengaduan
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Petugas yang ditugaskan
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Kecamatan (relasi FK ke tabel master)
     */
    public function kecamatanRef(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    /**
     * Desa/Kelurahan (relasi FK ke tabel master)
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Foto-foto bukti pengaduan & penyelesaian
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class);
    }

    /**
     * Foto bukti dari pelapor saja
     */
    public function evidencePhotos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class)->where('type', 'bukti');
    }

    /**
     * Foto bukti penyelesaian saja
     */
    public function resolutionPhotos(): HasMany
    {
        return $this->hasMany(ReportPhoto::class)->where('type', 'penyelesaian');
    }

    /**
     * Log aktivitas/audit trail
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ReportLog::class)->orderBy('created_at', 'desc');
    }

    // =========================================
    // SCOPES (untuk filter di controller)
    // =========================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeByKecamatan($query, string $kecamatan)
    {
        return $query->where('kecamatan', $kecamatan);
    }

    public function scopeByKecamatanId($query, int $kecamatanId)
    {
        return $query->where('kecamatan_id', $kecamatanId);
    }

    public function scopeByDesaId($query, int $desaId)
    {
        return $query->where('desa_id', $desaId);
    }

    /**
     * Laporan yang SLA-nya sudah terlewat dan belum selesai
     */
    public function scopeSlaOverdue($query)
    {
        return $query->whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->whereNotIn('status', ['selesai', 'ditolak']);
    }

    // =========================================
    // ACCESSORS (untuk tampilan)
    // =========================================

    /**
     * Badge HTML untuk status
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Pending</span>',
            'diproses' => '<span class="badge bg-info"><i class="bi bi-gear"></i> Diproses</span>',
            'selesai' => '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>',
            'ditolak' => '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>',
            default => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }

    /**
     * Badge HTML untuk prioritas
     */
    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'rendah' => '<span class="badge bg-secondary">Rendah</span>',
            'sedang' => '<span class="badge bg-primary">Sedang</span>',
            'tinggi' => '<span class="badge bg-warning text-dark">Tinggi</span>',
            'darurat' => '<span class="badge bg-danger">Darurat</span>',
            default => '<span class="badge bg-secondary">' . $this->priority . '</span>',
        };
    }

    /**
     * Label status dalam Bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai Ditangani',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }

    /**
     * Cek apakah SLA sudah terlewat
     */
    public function getIsSlaOverdueAttribute(): bool
    {
        if (!$this->sla_due_at) {
            return false;
        }
        return $this->sla_due_at->isPast() && !in_array($this->status, ['selesai', 'ditolak']);
    }

    /**
     * Nama pelapor (tersamarkan jika anonim)
     */
    public function getDisplayReporterNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Pelapor Anonim';
        }
        return $this->reporter_name;
    }

    /**
     * Nomor telepon pelapor (tersamarkan jika anonim)
     */
    public function getDisplayReporterPhoneAttribute(): string
    {
        if ($this->is_anonymous) {
            return '**********';
        }
        return $this->reporter_phone;
    }
}
