@extends('layouts.public')

@section('title', 'Semua Laporan Pengaduan')

@section('content')
<!-- Header Section -->
<section class="bg-dlh-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('public.landing') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Semua Laporan</li>
                    </ol>
                </nav>
                <h2 class="fw-bold mb-1">Semua Laporan Pengaduan</h2>
                <p class="opacity-75 mb-0">Transparansi pengaduan lingkungan masyarakat Kabupaten Demak</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Summary -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3 col-lg">
                <div class="text-center px-3 py-2 rounded-3 border {{ !request('status') ? 'bg-dlh-primary text-white shadow-sm border-success' : 'bg-success-subtle border-success-subtle' }}">
                    <a href="{{ route('public.reports.all', array_merge(request()->except('status', 'page'))) }}" class="text-decoration-none {{ !request('status') ? 'text-white' : 'text-success' }}">
                        <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
                        <div class="small {{ !request('status') ? 'opacity-75' : 'text-success opacity-75' }}">Total Laporan</div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg">
                <div class="text-center px-3 py-2 rounded-3 border {{ request('status') === 'pending' ? 'bg-warning text-dark shadow-sm border-warning' : 'bg-warning-subtle border-warning-subtle' }}">
                    <a href="{{ route('public.reports.all', array_merge(request()->except('page'), ['status' => 'pending'])) }}" class="text-decoration-none {{ request('status') === 'pending' ? 'text-dark' : 'text-warning-emphasis' }}">
                        <div class="fs-3 fw-bold">{{ $stats['pending'] }}</div>
                        <div class="small {{ request('status') === 'pending' ? 'opacity-75' : 'text-warning-emphasis opacity-75' }}">Menunggu</div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg">
                <div class="text-center px-3 py-2 rounded-3 border {{ request('status') === 'diproses' ? 'bg-info text-white shadow-sm border-info' : 'bg-info-subtle border-info-subtle' }}">
                    <a href="{{ route('public.reports.all', array_merge(request()->except('page'), ['status' => 'diproses'])) }}" class="text-decoration-none {{ request('status') === 'diproses' ? 'text-white' : 'text-info-emphasis' }}">
                        <div class="fs-3 fw-bold">{{ $stats['diproses'] }}</div>
                        <div class="small {{ request('status') === 'diproses' ? 'opacity-75' : 'text-info-emphasis opacity-75' }}">Diproses</div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg">
                <div class="text-center px-3 py-2 rounded-3 border {{ request('status') === 'selesai' ? 'bg-success text-white shadow-sm border-success' : 'bg-success-subtle border-success-subtle' }}">
                    <a href="{{ route('public.reports.all', array_merge(request()->except('page'), ['status' => 'selesai'])) }}" class="text-decoration-none {{ request('status') === 'selesai' ? 'text-white' : 'text-success' }}">
                        <div class="fs-3 fw-bold">{{ $stats['selesai'] }}</div>
                        <div class="small {{ request('status') === 'selesai' ? 'opacity-75' : 'text-success opacity-75' }}">Selesai</div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg">
                <div class="text-center px-3 py-2 rounded-3 border {{ request('status') === 'ditolak' ? 'bg-danger text-white shadow-sm border-danger' : 'bg-danger-subtle border-danger-subtle' }}">
                    <a href="{{ route('public.reports.all', array_merge(request()->except('page'), ['status' => 'ditolak'])) }}" class="text-decoration-none {{ request('status') === 'ditolak' ? 'text-white' : 'text-danger' }}">
                        <div class="fs-3 fw-bold">{{ $stats['ditolak'] }}</div>
                        <div class="small {{ request('status') === 'ditolak' ? 'opacity-75' : 'text-danger opacity-75' }}">Ditolak</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter -->
<section class="py-3 bg-white shadow-sm">
    <div class="container">
        <form action="{{ route('public.reports.all') }}" method="GET" class="row g-2 align-items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari lokasi, kecamatan, atau deskripsi..." value="{{ request('cari') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dlh-primary flex-grow-1"><i class="bi bi-funnel me-1"></i> Filter</button>
                @if(request()->hasAny(['cari', 'kategori', 'status']))
                    <a href="{{ route('public.reports.all') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</section>

<!-- Report Cards -->
<section class="py-5">
    <div class="container">
        @if($reports->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">
                    Menampilkan <strong>{{ $reports->firstItem() }}–{{ $reports->lastItem() }}</strong> dari <strong>{{ $reports->total() }}</strong> laporan
                </p>
            </div>

            <div class="row gy-4">
                @foreach($reports as $report)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden report-card">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill text-white" style="background-color: {{ $report->category->color }}">
                                <i class="{{ $report->category->icon }} me-1"></i> {{ $report->category->name }}
                            </span>
                            <span class="small text-muted"><i class="bi bi-clock me-1"></i> {{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="card-body p-4">
                            @if($report->evidencePhotos->count() > 0)
                                <img src="{{ $report->evidencePhotos->first()->photo_url }}" class="img-fluid rounded mb-3 w-100 object-fit-cover" style="height: 160px;" alt="Foto Laporan">
                            @else
                                <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" style="height: 160px;">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                            @endif
                            <h6 class="card-title fw-bold mb-2 text-truncate">{{ $report->address ?: 'Lokasi tidak tersedia' }}</h6>
                            @if($report->kecamatan)
                                <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $report->kecamatan }}</p>
                            @endif
                            <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $report->description }}
                            </p>
                            <div class="d-flex align-items-center">
                                @if($report->status === 'selesai')
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span class="fw-semibold small text-success">Selesai Ditangani</span>
                                @elseif($report->status === 'diproses')
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-gear-fill"></i>
                                    </div>
                                    <span class="fw-semibold small text-info">Sedang Diproses</span>
                                @elseif($report->status === 'ditolak')
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-x-lg"></i>
                                    </div>
                                    <span class="fw-semibold small text-danger">Ditolak</span>
                                @else
                                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span class="fw-semibold small text-warning">Menunggu Verifikasi</span>
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold text-muted">Tidak Ada Laporan Ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarian Anda atau <a href="{{ route('public.reports.all') }}" class="text-dlh-primary">lihat semua laporan</a>.</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .report-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .report-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.12) !important;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }
</style>
@endpush
