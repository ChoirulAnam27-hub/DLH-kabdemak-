<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — Staff DLH Kabupaten Demak
 * 
 * Digunakan untuk admin dan petugas lapangan.
 * Warga pelapor TIDAK perlu akun user.
 * 
 * @property string $role  'admin' atau 'petugas'
 * @property bool $is_active  Status aktif akun
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'nip',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // =========================================
    // RELASI
    // =========================================

    /**
     * Laporan yang ditugaskan ke petugas ini
     */
    public function assignedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'assigned_to');
    }

    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah petugas lapangan
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /**
     * Nama role yang ramah ditampilkan
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'petugas' => 'Petugas Lapangan',
            default => $this->role,
        };
    }

    /**
     * Hitung jumlah laporan aktif yang ditugaskan ke petugas ini
     * (status pending atau diproses, belum selesai/ditolak)
     */
    public function activeAssignedReportsCount(): int
    {
        return $this->assignedReports()
            ->whereIn('status', ['pending', 'diproses'])
            ->count();
    }
}
