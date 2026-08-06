<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Kecamatan;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin
     */
    public function index()
    {
        $today = Carbon::today();
        
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'hari_ini' => Report::whereDate('created_at', $today)->count(),
            'sla_overdue' => Report::slaOverdue()->count(),
        ];

        $kecamatans = Kecamatan::orderBy('name')->get();

        // Data untuk Chart (Laporan per Kategori)
        $categories = Category::withCount('reports')->get();
        $chartData = [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('reports_count')->toArray(),
            'colors' => $categories->pluck('color')->toArray(),
        ];

        // Laporan terbaru untuk tabel ringkasan
        $recentReports = Report::with(['category', 'evidencePhotos'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentReports', 'kecamatans'));
    }

    /**
     * Halaman Peta Sebaran Laporan
     */
    public function map()
    {
        $categories = Category::all();
        return view('admin.map', compact('categories'));
    }

    /**
     * API Endpoint untuk mengambil data marker peta
     */
    public function mapData(Request $request)
    {
        $query = Report::with(['category', 'assignedUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $reports = $query->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'ticket_code' => $report->ticket_code,
                'lat' => $report->latitude,
                'lng' => $report->longitude,
                'status' => $report->status,
                'status_label' => $report->status_label,
                'priority' => $report->priority,
                'category_name' => $report->category->name,
                'category_color' => $report->category->color,
                'category_icon' => $report->category->icon,
                'address' => $report->address,
                'created_at' => $report->created_at->format('d M Y H:i'),
                'url' => route('admin.reports.show', $report->id),
            ];
        });

        return response()->json($reports);
    }
}
