<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;

use Illuminate\Http\Request;

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

    /**
     * Tampilkan semua laporan (publik, read-only)
     */
    public function allReports(Request $request)
    {
        $query = Report::with(['category', 'evidencePhotos', 'resolutionPhotos'])
            ->latest();

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'diproses', 'selesai', 'ditolak'])) {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        // Pencarian alamat/deskripsi
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('kecamatan', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(12)->withQueryString();

        // Statistik ringkas
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'ditolak' => Report::where('status', 'ditolak')->count(),
        ];

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('public.all-reports', compact('reports', 'stats', 'categories'));
    }
}
