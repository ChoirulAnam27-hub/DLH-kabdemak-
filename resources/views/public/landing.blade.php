@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="bg-dlh-primary text-white py-5 position-relative overflow-hidden">
    <div class="container py-5 position-relative z-1">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Lapor Sampah & Pencemaran di Demak</h1>
                <p class="lead mb-4 opacity-75">Bantu kami menjaga lingkungan Kabupaten Demak tetap bersih dan sehat. Laporkan penumpukan sampah liar, limbah, atau pencemaran dengan mudah.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('public.report.create') }}" class="btn btn-light btn-lg text-dlh-primary fw-bold px-4 rounded-pill">
                        <i class="bi bi-megaphone-fill me-2"></i> Buat Laporan
                    </a>
                    <a href="{{ route('public.track.index') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                        <i class="bi bi-search me-2"></i> Lacak Laporan
                    </a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                <div class="bg-white p-4 rounded-4 shadow-lg text-dark transform-tilt">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning text-white rounded-circle p-2 me-3">
                            <i class="bi bi-bell-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Respon Cepat</h5>
                            <span class="text-muted small">Tim kami siap menindaklanjuti</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <h3 class="fw-bold text-dlh-primary mb-0">{{ $stats['total'] }}</h3>
                            <span class="small text-muted">Total Laporan</span>
                        </div>
                        <div class="col-4 border-start border-end">
                            <h3 class="fw-bold text-warning mb-0">{{ $stats['diproses'] }}</h3>
                            <span class="small text-muted">Diproses</span>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-success mb-0">{{ $stats['selesai'] }}</h3>
                            <span class="small text-muted">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Decorative background elements -->
    <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 300px; height: 300px; top: -100px; right: -50px;"></div>
    <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 150px; height: 150px; bottom: 50px; right: 200px;"></div>
</section>

<!-- How it works -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h6 class="text-dlh-primary fw-bold text-uppercase tracking-wide">Cara Kerja</h6>
            <h2 class="fw-bold">Tiga Langkah Mudah Melapor</h2>
        </div>
        <div class="row text-center gy-4">
            <div class="col-md-4">
                <div class="step-icon">
                    <i class="bi bi-camera-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">1. Foto Kejadian</h5>
                <p class="text-muted">Ambil foto penumpukan sampah atau pencemaran yang Anda temukan di lokasi.</p>
            </div>
            <div class="col-md-4">
                <div class="step-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">2. Isi Lokasi & Detail</h5>
                <p class="text-muted">Sistem akan otomatis mendeteksi lokasi GPS Anda, lalu tambahkan sedikit penjelasan.</p>
            </div>
            <div class="col-md-4">
                <div class="step-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">3. Tunggu Penanganan</h5>
                <p class="text-muted">Anda akan mendapat Kode Tiket. Petugas kami akan segera meluncur ke lokasi untuk menangani.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Resolved Reports -->
@if($recentReports->count() > 0)
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-dlh-primary fw-bold text-uppercase tracking-wide">Riwayat Laporan</h6>
                <h2 class="fw-bold mb-0">Pengaduan Terbaru</h2>
            </div>
            <a href="{{ route('public.track.index') }}" class="btn btn-outline-secondary d-none d-md-block">Lihat Semua Laporan</a>
        </div>

        <div class="row gy-4">
            @foreach($recentReports as $report)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <span class="badge rounded-pill text-white" style="background-color: {{ $report->category->color }}">
                            <i class="{{ $report->category->icon }} me-1"></i> {{ $report->category->name }}
                        </span>
                        <span class="small text-muted"><i class="bi bi-clock me-1"></i> {{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="card-body p-4">
                        @if($report->resolutionPhotos->count() > 0)
                            <img src="{{ $report->resolutionPhotos->first()->photo_url }}" class="img-fluid rounded mb-3 w-100 object-fit-cover" style="height: 150px;" alt="Foto Penanganan">
                        @elseif($report->evidencePhotos->count() > 0)
                            <img src="{{ $report->evidencePhotos->first()->photo_url }}" class="img-fluid rounded mb-3 w-100 object-fit-cover" style="height: 150px;" alt="Foto Laporan">
                        @endif
                        <h6 class="card-title fw-bold mb-3 text-truncate">{{ $report->address }}</h6>
                        <p class="card-text text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $report->description }}
                        </p>
                        <div class="d-flex align-items-center mt-auto">
                            @if($report->status === 'selesai')
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-lg fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Selesai Ditangani</h6>
                                </div>
                            @elseif($report->status === 'diproses')
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-gear-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Sedang Diproses</h6>
                                </div>
                            @elseif($report->status === 'ditolak')
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-x-lg fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Ditolak</h6>
                                </div>
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Menunggu</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top border-light p-3 px-4 text-center">
                        <a href="{{ route('public.track.show', $report->ticket_code) }}" class="text-decoration-none text-dlh-primary fw-bold small">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('public.track.index') }}" class="btn btn-outline-secondary">Lihat Semua Laporan</a>
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
    .tracking-wide { letter-spacing: 2px; }
    .transform-tilt { transform: rotate(-2deg); transition: transform 0.3s ease; }
    .transform-tilt:hover { transform: rotate(0); }
</style>
@endpush
