<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Services\ReportService;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Get desas by kecamatan for dependent dropdown
     */
    public function getDesas(Kecamatan $kecamatan)
    {
        return response()->json($kecamatan->desas);
    }

    /**
     * Check if a similar report already exists nearby
     */
    public function checkDuplicate(Request $request, ReportService $reportService)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'category_id' => 'required|integer',
        ]);

        $isDuplicate = $reportService->checkDuplicate(
            $request->lat, 
            $request->lng, 
            $request->category_id
        );

        return response()->json(['is_duplicate' => $isDuplicate]);
    }
}
