<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Api\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Pengaduan DLH Kabupaten Demak
|--------------------------------------------------------------------------
|
| Route dibagi menjadi 3 grup:
| 1. Public (warga) — tanpa login
| 2. Auth (login/logout)
| 3. Admin (dashboard) — memerlukan login
|
*/

// =============================================
// 1. ROUTE PUBLIC (Sisi Warga - Tanpa Login)
// =============================================
Route::get('/', [PublicReportController::class, 'home'])->name('home');
Route::get('/lapor', [PublicReportController::class, 'create'])->name('report.create');
Route::post('/lapor', [PublicReportController::class, 'store'])->name('report.store');
Route::get('/lapor/sukses/{ticketCode}', [PublicReportController::class, 'success'])->name('report.success');
Route::get('/lacak', [PublicReportController::class, 'track'])->name('report.track');
Route::post('/lacak', [PublicReportController::class, 'trackResult'])->name('report.track.result');

// =============================================
// 2. ROUTE API (Untuk AJAX requests)
// =============================================
Route::prefix('api')->group(function () {
    Route::get('/kecamatans', [LocationController::class, 'getKecamatans']);
    Route::get('/kelurahans/{kecamatanId}', [LocationController::class, 'getKelurahans']);
    Route::get('/map/markers', [MapController::class, 'markers'])->name('api.map.markers');
    Route::get('/map/heatmap', [MapController::class, 'heatmapData'])->name('api.map.heatmap');
});

// =============================================
// 3. ROUTE AUTENTIKASI (Login/Logout)
// =============================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =============================================
// 4. ROUTE ADMIN (Dashboard - Perlu Login)
// =============================================
Route::prefix('admin')->middleware(['auth', 'role:admin,petugas'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::put('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
    Route::post('/reports/{report}/resolved', [ReportController::class, 'uploadResolved'])->name('reports.uploadResolved');

    // Export
    Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');

    // Peta Interaktif
    Route::get('/map', [MapController::class, 'index'])->name('map');
});
