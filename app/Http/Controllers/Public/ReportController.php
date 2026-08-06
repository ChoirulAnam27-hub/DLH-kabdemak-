<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Kecamatan;
use App\Models\Report;
use App\Services\ReportService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;
    protected WhatsAppService $waService;

    public function __construct(ReportService $reportService, WhatsAppService $waService)
    {
        $this->reportService = $reportService;
        $this->waService = $waService;
    }

    /**
     * Tampilkan form pengaduan (dengan Leaflet Map)
     */
    public function create()
    {
        $categories = Category::active()->get();
        
        // Daftar kecamatan di Demak (dari database)
        $kecamatans = Kecamatan::with('desas')->orderBy('name')->get();

        return view('public.report-form', compact('categories', 'kecamatans'));
    }

    /**
     * Proses simpan pengaduan baru
     */
    public function store(Request $request)
    {
        $isAnonymous = $request->boolean('is_anonymous');

        $rules = [
            'reporter_email' => 'nullable|email|max:100',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'nullable|exists:desas,id',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:5120', // maks 5MB per foto
        ];

        if (!$isAnonymous) {
            $rules['reporter_name'] = 'required|string|max:100';
            $rules['reporter_phone'] = 'required|string|max:20';
        } else {
            $rules['reporter_name'] = 'nullable|string|max:100';
            $rules['reporter_phone'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules);

        $validated['is_anonymous'] = $isAnonymous;
        if ($isAnonymous) {
            $validated['reporter_name'] = $validated['reporter_name'] ?: 'Anonim';
            $validated['reporter_phone'] = $validated['reporter_phone'] ?: '0000000000';
        }

        $kec = Kecamatan::find($validated['kecamatan_id']);
        $validated['kecamatan'] = $kec->name ?? '';
        
        if (!empty($validated['desa_id'])) {
            $desa = \App\Models\Desa::find($validated['desa_id']);
            $validated['kelurahan'] = $desa->name ?? '';
        }

        $photos = $request->file('photos') ?? [];
        
        // Batasi maksimal 3 foto
        if (count($photos) > 3) {
            return back()->withInput()->withErrors(['photos' => 'Maksimal 3 foto yang diperbolehkan.']);
        }

        try {
            $report = $this->reportService->createReport($validated, $photos);
            $this->waService->sendTicketCreated($report);
            return redirect()->route('public.report.success', ['ticket' => $report->ticket_code]);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman sukses dengan kode tiket
     */
    public function success(string $ticketCode)
    {
        $report = Report::where('ticket_code', $ticketCode)->firstOrFail();
        return view('public.report-success', compact('report'));
    }
}
