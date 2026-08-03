<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

/**
 * Controller Map — Peta interaktif sebaran laporan.
 * Menampilkan semua laporan pada peta Leaflet dengan marker clustering & heatmap.
 */
class MapController extends Controller
{
    /**
     * Halaman peta interaktif full-screen.
     */
    public function index()
    {
        return view('admin.map.index');
    }

    /**
     * API endpoint: data marker untuk peta (JSON).
     */
    public function markers()
    {
        $reports = Report::select(
            'id', 'ticket_code', 'category', 'status', 'priority',
            'latitude', 'longitude', 'address', 'kecamatan',
            'reporter_name', 'created_at'
        )->get();

        return response()->json($reports);
    }

    /**
     * API endpoint: data heatmap (array [lat, lng, intensity]).
     */
    public function heatmapData()
    {
        $reports = Report::select('latitude', 'longitude', 'status')
            ->get()
            ->map(function ($r) {
                $intensity = match ($r->status) {
                    'pending' => 0.8,
                    'diproses' => 0.5,
                    'selesai' => 0.2,
                    'ditolak' => 0.1,
                    default => 0.5,
                };
                return [(float) $r->latitude, (float) $r->longitude, $intensity];
            });

        return response()->json($reports);
    }
}
