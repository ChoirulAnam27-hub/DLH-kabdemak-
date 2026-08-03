<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller Admin/Report — Manajemen Laporan oleh Admin & Petugas.
 *
 * Fitur:
 * - Daftar laporan + filter multi-kriteria
 * - Detail laporan + timeline status
 * - Update status & disposisi petugas
 * - Upload foto bukti penyelesaian
 * - Export PDF & Excel
 */
class ReportController extends Controller
{
    /**
     * Daftar laporan dengan filter & pagination.
     */
    public function index(Request $request)
    {
        $query = Report::with('assignedUser');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to . ' 23:59:59']);
        }

        // Pencarian berdasarkan kode tiket / nama pelapor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('reporter_name', 'like', "%{$search}%")
                    ->orWhere('reporter_phone', 'like', "%{$search}%");
            });
        }

        // Jika petugas, hanya tampilkan laporan yang di-assign ke mereka
        if (auth()->user()->isPetugas()) {
            $query->where('assigned_to', auth()->id());
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        // Data untuk dropdown filter
        $kecamatans = Kecamatan::orderBy('name')->pluck('name');
        $petugasList = User::where('role', 'petugas')->where('is_active', true)->get();

        return view('admin.reports.index', compact('reports', 'kecamatans', 'petugasList'));
    }

    /**
     * Detail laporan tunggal + timeline riwayat status.
     */
    public function show(Report $report)
    {
        $report->load(['assignedUser', 'histories.user']);
        $petugasList = User::where('role', 'petugas')->where('is_active', true)->get();

        return view('admin.reports.show', compact('report', 'petugasList'));
    }

    /**
     * Update status laporan & disposisi petugas.
     */
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'assigned_to' => 'nullable|exists:users,id',
            'note' => 'nullable|string|max:500',
            'priority' => 'nullable|in:rendah,sedang,tinggi,darurat',
        ]);

        $oldStatus = $report->status;
        $newStatus = $request->status;

        // Update laporan
        $report->update([
            'status' => $newStatus,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority ?? $report->priority,
        ]);

        // Jika selesai, catat waktu penyelesaian
        if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
            $report->update(['resolved_at' => now()]);
        }

        // Catat riwayat perubahan status
        ReportHistory::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $request->note ?? 'Status diperbarui oleh ' . auth()->user()->name,
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Upload foto bukti penyelesaian.
     */
    public function uploadResolved(Request $request, Report $report)
    {
        $request->validate([
            'resolved_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'resolved_note' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('resolved_photo')) {
            $photo = $request->file('resolved_photo');
            $filename = 'resolved_' . time() . '_' . Str::random(8) . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/resolved'), $filename);

            $report->update([
                'resolved_photo' => 'uploads/resolved/' . $filename,
                'resolved_note' => $request->resolved_note,
                'resolved_at' => now(),
                'status' => 'selesai',
            ]);

            // Catat riwayat
            ReportHistory::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'old_status' => $report->getOriginal('status'),
                'new_status' => 'selesai',
                'note' => 'Bukti penyelesaian diunggah. ' . ($request->resolved_note ?? ''),
            ]);
        }

        return back()->with('success', 'Bukti penyelesaian berhasil diunggah.');
    }

    /**
     * Export laporan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Report::with('assignedUser');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to . ' 23:59:59']);
        }

        $reports = $query->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.reports-pdf', compact('reports'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Pengaduan_DLH_Demak_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export laporan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ReportsExport($request),
            'Laporan_Pengaduan_DLH_Demak_' . date('Y-m-d') . '.xlsx'
        );
    }
}
