<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller PublicReport — Menangani pengaduan dari sisi warga.
 *
 * Fitur:
 * - Form pengaduan (tanpa login)
 * - Deteksi GPS otomatis
 * - Upload foto bukti
 * - Auto-generate kode tiket
 * - Lacak status laporan
 */
class PublicReportController extends Controller
{
    /**
     * Halaman utama / landing page.
     */
    public function home()
    {
        // Statistik untuk ditampilkan di landing page
        $stats = [
            'total' => Report::count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'pending' => Report::where('status', 'pending')->count(),
        ];

        $latestReports = Report::latest()->take(5)->get();

        return view('public.home', compact('stats', 'latestReports'));
    }

    /**
     * Tampilkan form pengaduan baru.
     */
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('public.report.create', compact('kecamatans'));
    }

    /**
     * Simpan laporan pengaduan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:100',
            'reporter_phone' => 'required|string|max:20',
            'reporter_email' => 'nullable|email|max:100',
            'category' => 'required|in:sampah,pencemaran_air,pencemaran_udara,lainnya',
            'description' => 'required|string|min:10',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'required|string',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        // Upload foto bukti
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'report_' . time() . '_' . Str::random(8) . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/reports'), $filename);
            $validated['photo_path'] = 'uploads/reports/' . $filename;
        }

        // Buat laporan baru (kode tiket auto-generate via Model)
        $report = Report::create($validated);

        // Catat riwayat status awal
        ReportHistory::create([
            'report_id' => $report->id,
            'user_id' => null,
            'old_status' => null,
            'new_status' => 'pending',
            'note' => 'Laporan diterima dari warga.',
        ]);

        return redirect()->route('report.success', $report->ticket_code);
    }

    /**
     * Halaman sukses setelah kirim laporan — tampilkan kode tiket.
     */
    public function success(string $ticketCode)
    {
        $report = Report::where('ticket_code', $ticketCode)->firstOrFail();
        return view('public.report.success', compact('report'));
    }

    /**
     * Halaman form lacak laporan.
     */
    public function track()
    {
        return view('public.report.track');
    }

    /**
     * Proses pencarian laporan berdasarkan kode tiket atau nomor HP.
     */
    public function trackResult(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3',
        ]);

        $search = $request->input('search');

        // Cari berdasarkan kode tiket atau nomor telepon
        $reports = Report::where('ticket_code', $search)
            ->orWhere('reporter_phone', $search)
            ->latest()
            ->get();

        return view('public.report.track-result', compact('reports', 'search'));
    }
}
