<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\ReportController;
use App\Http\Controllers\Public\TrackController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportManagementController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Middleware\RoleMiddleware;

// =========================================
// PUBLIC ROUTES
// =========================================
Route::get('/', [LandingController::class, 'index'])->name('public.landing');

Route::prefix('lapor')->group(function () {
    Route::get('/', [ReportController::class, 'create'])->name('public.report.create');
    Route::post('/', [ReportController::class, 'store'])->name('public.report.store');
    Route::get('/sukses/{ticket}', [ReportController::class, 'success'])->name('public.report.success');
});

Route::prefix('lacak')->group(function () {
    Route::get('/', [TrackController::class, 'index'])->name('public.track.index');
    Route::post('/', [TrackController::class, 'search'])->name('public.track.search');
    Route::get('/{ticket}', [TrackController::class, 'show'])->name('public.track.show');
});

// Halaman publik: Lihat semua laporan (read-only)
Route::get('/semua-laporan', [LandingController::class, 'allReports'])->name('public.reports.all');

// =========================================
// ADMIN ROUTES
// =========================================
// (Menggunakan auth bawaan Laravel. View login dibuat terpisah di Frontend Phase)
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('login.post')->middleware('guest');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// Protected Admin Routes
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/peta', [DashboardController::class, 'map'])->name('admin.map');
    
    // Manajemen Laporan
    Route::prefix('laporan')->group(function () {
        // Halaman Index (Tabel Laporan) khusus Admin
        Route::get('/', [ReportManagementController::class, 'index'])
            ->name('admin.reports.index')
            ->middleware(RoleMiddleware::class . ':admin');
            
        // Halaman Tugas Saya khusus Petugas
        Route::get('/tugas-saya', [ReportManagementController::class, 'myTasks'])
            ->name('admin.reports.my-tasks')
            ->middleware(RoleMiddleware::class . ':petugas');

        Route::get('/{report}', [ReportManagementController::class, 'show'])->name('admin.reports.show');
        Route::put('/{report}/status', [ReportManagementController::class, 'updateStatus'])->name('admin.reports.update-status');
        Route::put('/{report}/assign', [ReportManagementController::class, 'assign'])->name('admin.reports.assign')->middleware(RoleMiddleware::class . ':admin'); // Hanya admin
        Route::post('/{report}/foto', [ReportManagementController::class, 'uploadResolution'])->name('admin.reports.upload-resolution');
    });

    // Export (Hanya Admin)
    Route::prefix('export')->middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::get('/pdf', [ExportController::class, 'exportPdf'])->name('admin.export.pdf');
        Route::get('/excel', [ExportController::class, 'exportExcel'])->name('admin.export.excel');
    });

    // Manajemen Petugas Lapangan (Hanya Admin)
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::resource('petugas', \App\Http\Controllers\Admin\PetugasController::class)->names('admin.petugas')->except(['show']);
    });
});

// =========================================
// API ROUTES
// =========================================
Route::get('/api/map-data', [DashboardController::class, 'mapData'])->name('api.map-data');
Route::get('/api/kecamatan/{kecamatan}/desas', [\App\Http\Controllers\Public\KecamatanController::class, 'getDesas'])->name('api.kecamatan.desas');
Route::post('/api/check-duplicate', [\App\Http\Controllers\Public\KecamatanController::class, 'checkDuplicate'])->name('api.check-duplicate');
