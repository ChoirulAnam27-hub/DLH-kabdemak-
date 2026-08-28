@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <span class="text-muted">Ringkasan data laporan pengaduan masyarakat.</span>
    </div>
</div>

<!-- Stat Cards -->
<div class="row gy-4 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-white">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-inbox-fill fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4>
                    <span class="text-muted" style="font-size: 0.75rem;">Total Laporan</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-white">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $stats['pending'] }}</h4>
                    <span class="text-muted" style="font-size: 0.75rem;">Pending</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card border-0 bg-white">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-gear-fill fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $stats['diproses'] }}</h4>
                    <span class="text-muted" style="font-size: 0.75rem;">Sedang Diproses</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-6 col-sm-6">
        <div class="card border-0 bg-white">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $stats['selesai'] }}</h4>
                    <span class="text-muted" style="font-size: 0.75rem;">Selesai</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card border-0 bg-white position-relative overflow-hidden">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="bi bi-exclamation-triangle-fill fs-5 position-relative">
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle sla-pulse"></span>
                    </i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-danger">{{ $stats['sla_overdue'] ?? 0 }}</h4>
                    <span class="text-danger fw-bold" style="font-size: 0.75rem;">SLA Terlewat</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4">
    <!-- Chart Section -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Laporan per Kategori</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Info Mini Map -->
    <div class="col-lg-5">
        <div class="card h-100 bg-success text-white overflow-hidden position-relative" style="border: none;">
            <!-- Background Image Map Placeholder -->
            <div class="position-absolute w-100 h-100" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Demak_dalam_Jawa_Tengah.svg/500px-Demak_dalam_Jawa_Tengah.svg.png'); background-size: cover; background-position: center; opacity: 0.1; filter: grayscale(100%);"></div>
            
            <div class="card-body p-4 d-flex flex-column justify-content-between position-relative z-1">
                <div>
                    <h5 class="fw-bold mb-1">Peta Sebaran Laporan</h5>
                    <p class="opacity-75 small">Visualisasi titik lokasi permasalahan lingkungan yang dilaporkan warga di seluruh wilayah Kab. Demak.</p>
                </div>
                <div class="mt-4">
                    <h1 class="display-3 fw-bold mb-0">{{ $stats['hari_ini'] }}</h1>
                    <p class="opacity-75">Laporan Baru Hari Ini</p>
                    <a href="{{ route('admin.map') }}" class="btn btn-light text-success fw-bold px-4 rounded-pill mt-2">Buka Peta Interaktif <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Laporan Terbaru Masuk</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tiket</th>
                                <th>Foto</th>
                                <th>Kategori</th>
                                <th>Pelapor</th>
                                <th>Kecamatan</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td class="ps-4"><span class="fw-bold text-dark">{{ $report->ticket_code }}</span><br><small class="text-muted">{{ $report->created_at->diffForHumans() }}</small></td>
                                <td>
                                    @if($report->evidencePhotos->count() > 0)
                                    <a href="{{ $report->evidencePhotos->first()->photo_url }}" target="_blank">
                                        <img src="{{ $report->evidencePhotos->first()->photo_url }}" class="rounded shadow-sm object-fit-cover" width="60" height="40" alt="Foto Laporan">
                                    </a>
                                    @else
                                    <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px; font-size: 0.75rem;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill text-white" style="background-color: {{ $report->category->color }}">
                                        <i class="{{ $report->category->icon }} me-1"></i> {{ $report->category->name }}
                                    </span>
                                </td>
                                <td>{{ $report->display_reporter_name }}</td>
                                <td>{{ $report->kecamatan }}</td>
                                <td>{!! $report->status_badge !!}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-light text-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data laporan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const data = @json($chartData);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.data,
                    backgroundColor: data.colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { family: "'Inter', sans-serif" },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '75%'
            }
        });
    });
</script>
<style>
    @keyframes slaPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .sla-pulse {
        animation: slaPulse 2s infinite;
    }
</style>
@endpush
