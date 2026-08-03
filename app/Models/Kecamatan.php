<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Kecamatan — Data Kecamatan Kabupaten Demak.
 * Berelasi ke kelurahan/desa di dalamnya.
 */
class Kecamatan extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude'];

    public function kelurahans(): HasMany
    {
        return $this->hasMany(Kelurahan::class);
    }
}
