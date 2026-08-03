<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

/**
 * Controller API Location — Menyediakan data wilayah untuk dropdown form.
 */
class LocationController extends Controller
{
    /**
     * Daftar semua kecamatan.
     */
    public function getKecamatans()
    {
        $kecamatans = Kecamatan::orderBy('name')->get(['id', 'name']);
        return response()->json($kecamatans);
    }

    /**
     * Daftar kelurahan berdasarkan kecamatan.
     */
    public function getKelurahans($kecamatanId)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($kelurahans);
    }
}
