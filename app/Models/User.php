<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — Admin & Petugas Lapangan DLH Demak.
 *
 * Role:
 * - admin: Akses penuh (dashboard, disposisi, export, manajemen user)
 * - petugas: Akses terbatas (lihat laporan yang di-assign, upload bukti selesai)
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
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Cek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Petugas Lapangan.
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /**
     * Relasi: Laporan yang ditugaskan ke petugas ini.
     */
    public function assignedReports()
    {
        return $this->hasMany(Report::class, 'assigned_to');
    }
}
