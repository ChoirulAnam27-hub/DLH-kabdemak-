<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

/**
 * Controller Dashboard — Statistik & ringkasan untuk Admin DLH.
 *
 * Menampilkan:
 * - Total laporan per status
 * - Chart distribusi per kategori & kecamatan
 * - Laporan terbaru
 * - Mini map sebaran lokasi
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'ditolak' => Report::where('status', 'ditolak')->count(),
        ];

        // Statistik bulan ini
        $thisMonth = Carbon::now()->startOfMonth();
        $stats['bulan_ini'] = Report::where('created_at', '>=', $thisMonth)->count();

        // Data chart: laporan per kategori
        $chartCategories = Report::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Data chart: laporan per kecamatan (top 10)
        $chartKecamatan = Report::selectRaw('kecamatan, COUNT(*) as total')
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'kecamatan')
            ->toArray();

        // Data chart: laporan per bulan (6 bulan terakhir)
        $chartMonthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Report::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $chartMonthly[$month->translatedFormat('M Y')] = $count;
        }

        // Laporan terbaru
        $latestReports = Report::with('assignedUser')
            ->latest()
            ->take(10)
            ->get();

        // Data marker peta (semua laporan)
        $mapReports = Report::select('id', 'ticket_code', 'category', 'status', 'latitude', 'longitude', 'address', 'created_at')
            ->latest()
            ->take(200)
            ->get();

        // Jumlah petugas aktif
        $totalPetugas = User::where('role', 'petugas')->where('is_active', true)->count();

        return view('admin.dashboard', compact(
            'stats',
            'chartCategories',
            'chartKecamatan',
            'chartMonthly',
            'latestReports',
            'mapReports',
            'totalPetugas'
        ));
    }
}
