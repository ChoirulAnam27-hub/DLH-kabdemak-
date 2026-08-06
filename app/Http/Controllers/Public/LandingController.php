<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page utama
     */
    public function index()
    {
        // Statistik untuk ditampilkan di landing page
        $stats = [
            'total' => Report::count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
        ];

        // Riwayat laporan terbaru
        $recentReports = Report::with(['category', 'evidencePhotos', 'resolutionPhotos'])
            ->latest()
            ->take(6)
            ->get();

        return view('public.landing', compact('stats', 'recentReports'));
    }
}
