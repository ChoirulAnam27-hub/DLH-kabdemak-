<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Tampilkan form lacak laporan
     */
    public function index()
    {
        return view('public.track');
    }

    /**
     * Proses pencarian laporan
     */
    public function search(Request $request)
    {
        $request->validate([
            'search_key' => 'required|string',
        ]);

        $key = $request->input('search_key');

        // Cari berdasarkan kode tiket ATAU nomor HP
        $reports = Report::where('ticket_code', $key)
            ->orWhere('reporter_phone', $key)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'Laporan tidak ditemukan. Periksa kembali Kode Tiket atau Nomor HP Anda.');
        }

        // Jika hanya ada 1 hasil, langsung ke detail
        if ($reports->count() === 1) {
            return redirect()->route('public.track.show', ['ticket' => $reports->first()->ticket_code]);
        }

        // Jika banyak hasil (berdasarkan no HP), tampilkan list
        return view('public.track-list', compact('reports', 'key'));
    }

    /**
     * Tampilkan detail status laporan (timeline)
     */
    public function show(string $ticketCode)
    {
        $report = Report::with(['category', 'logs.user', 'evidencePhotos', 'resolutionPhotos'])
            ->where('ticket_code', $ticketCode)
            ->firstOrFail();

        return view('public.track-result', compact('report'));
    }
}
