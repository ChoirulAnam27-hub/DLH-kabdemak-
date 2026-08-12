<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Kecamatan;
use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class ReportManagementController extends Controller
{
    protected ReportService $reportService;
    protected WhatsAppService $waService;

    public function __construct(ReportService $reportService, WhatsAppService $waService)
    {
        $this->reportService = $reportService;
        $this->waService = $waService;
    }

    /**
     * Tampilkan daftar laporan (dengan filter)
     */
    public function index(Request $request)
    {
        $query = Report::with(['category', 'assignedUser', 'evidencePhotos'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }
        if ($request->filled('kecamatan_id')) {
            $query->byKecamatanId($request->kecamatan_id);
        }
        if ($request->filled('desa_id')) {
            $query->byDesaId($request->desa_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                  ->orWhere('reporter_name', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(15)->withQueryString();
        $categories = Category::active()->get();
        $kecamatans = Kecamatan::orderBy('name')->get();
        
        return view('admin.reports.index', compact('reports', 'categories', 'kecamatans'));
    }

    /**
     * Tampilkan daftar tugas untuk Petugas (Mobile Friendly)
     */
    public function myTasks(Request $request)
    {
        $query = Report::with(['category', 'evidencePhotos'])
            ->where('assigned_to', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan yang belum selesai
            $query->whereIn('status', ['pending', 'diproses']);
        }

        $reports = $query->paginate(10)->withQueryString();
        
        return view('admin.reports.my-tasks', compact('reports'));
    }

    /**
     * Tampilkan detail laporan
     */
    public function show(Report $report)
    {
        $report->load(['category', 'logs.user', 'evidencePhotos', 'resolutionPhotos', 'assignedUser', 'kecamatanRef', 'desa']);
        $petugas = User::where('role', 'petugas')->where('is_active', true)->withCount(['assignedReports' => function($q) {
            $q->whereIn('status', ['pending', 'diproses']);
        }])->get();
        
        return view('admin.reports.show', compact('report', 'petugas'));
    }

    /**
     * Update status laporan
     */
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,ditolak|string|nullable'
        ]);

        $this->reportService->updateStatus(
            $report, 
            $request->status, 
            auth()->id(), 
            $request->notes ?? '',
            $request->rejection_reason ?? ''
        );
        
        if ($request->status === 'selesai') {
            $this->waService->sendResolved($report);
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Disposisi laporan ke petugas
     */
    public function assign(Request $request, Report $report)
    {
        $request->validate([
            'petugas_id' => 'required|exists:users,id',
        ]);

        $this->reportService->assignPetugas($report, $request->petugas_id, auth()->id());
        $petugasUser = User::find($request->petugas_id);
        $this->waService->sendAssigned($report, $petugasUser);

        return back()->with('success', 'Laporan berhasil didisposisikan ke petugas.');
    }

    /**
     * Upload foto penyelesaian
     */
    public function uploadResolution(Request $request, Report $report)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $this->reportService->addResolutionPhoto(
            $report, 
            $request->file('photo'), 
            auth()->id(), 
            $request->caption ?? ''
        );

        return back()->with('success', 'Foto penyelesaian berhasil diunggah.');
    }
}
