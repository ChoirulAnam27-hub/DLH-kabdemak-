<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ExportController extends Controller
{
    /**
     * Export daftar laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Report::with(['category', 'assignedUser'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('created_at', $request->bulan)
                  ->whereYear('created_at', $request->tahun);
        }
        if ($request->filled('kecamatan_id')) {
            $query->byKecamatanId($request->kecamatan_id);
        }

        $reports = $query->get();
        $filters = $request->only(['status', 'bulan', 'tahun', 'kecamatan_id']);

        if (!empty($filters['kecamatan_id'])) {
            $filters['kecamatan_name'] = Kecamatan::find($filters['kecamatan_id'])->name ?? '';
        }

        $pdf = Pdf::loadView('exports.reports-pdf', compact('reports', 'filters'));
        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'Rekap_Laporan_DLH_Demak_' . date('Ymd_His') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Export daftar laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['status', 'bulan', 'tahun', 'kecamatan_id']);
        $filename = 'Rekap_Laporan_DLH_Demak_' . date('Ymd_His') . '.xlsx';
        
        return Excel::download(new ReportsExport($filters), $filename);
    }
}
