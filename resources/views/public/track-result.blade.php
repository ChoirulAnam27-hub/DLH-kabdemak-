@extends('layouts.public')

@section('title', 'Detail Laporan - ' . $report->ticket_code)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="fw-bold mb-0">Tiket: <span class="text-dlh-primary">{{ $report->ticket_code }}</span></h2>
                <a href="{{ route('public.track.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>

            <div class="row gy-4">
                <!-- Kolom Kiri: Detail -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                                <div>
                                    <span class="text-muted small d-block mb-1">Status Laporan</span>
                                    <div class="fs-5">{!! $report->status_badge !!}</div>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted small d-block mb-1">Prioritas</span>
                                    {!! $report->priority_badge !!}
                                </div>
                            </div>
                            
                            @if($report->status === 'ditolak' && $report->rejection_reason)
                            <div class="alert alert-danger mb-4 rounded-3">
                                <h6 class="fw-bold"><i class="bi bi-x-circle-fill me-2"></i>Alasan Penolakan</h6>
                                <p class="mb-0">{{ $report->rejection_reason }}</p>
                            </div>
                            @endif

                            <div class="mb-4">
                                <span class="badge rounded-pill text-white mb-2" style="background-color: {{ $report->category->color }}">
                                    <i class="{{ $report->category->icon }} me-1"></i> {{ $report->category->name }}
                                </span>
                                <p class="fs-5 mb-0">{{ $report->description }}</p>
                            </div>

                            <div class="bg-light p-3 rounded-3 mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi</h6>
                                <p class="mb-1">{{ $report->address }}</p>
                                <p class="text-muted small mb-0">Kel. {{ $report->kelurahan ?? '-' }}, Kec. {{ $report->kecamatan }}</p>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="bi bi-map me-1"></i> Buka di Google Maps
                                </a>
                            </div>

                            <!-- Foto Bukti dari Pelapor -->
                            @if($report->evidencePhotos->count() > 0)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-camera me-2"></i>Foto Laporan Warga</h6>
                                <div class="row g-2">
                                    @foreach($report->evidencePhotos as $photo)
                                    <div class="col-4">
                                        <a href="{{ $photo->photo_url }}" target="_blank">
                                            <img src="{{ $photo->photo_url }}" class="img-fluid rounded border shadow-sm" alt="Bukti Foto" style="height: 120px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Foto Penyelesaian dari Petugas -->
                            @if($report->resolutionPhotos->count() > 0)
                            <div class="mt-4 pt-4 border-top border-success">
                                <h6 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>Foto Hasil Penanganan</h6>
                                <div class="row g-2">
                                    @foreach($report->resolutionPhotos as $photo)
                                    <div class="col-6">
                                        <div class="card border-0">
                                            <a href="{{ $photo->photo_url }}" target="_blank">
                                                <img src="{{ $photo->photo_url }}" class="img-fluid rounded border border-success border-2" alt="Foto Penyelesaian" style="height: 180px; width: 100%; object-fit: cover;">
                                            </a>
                                            @if($photo->caption)
                                            <div class="card-footer bg-white px-0 border-0 pt-2 text-muted small">
                                                <i class="bi bi-chat-quote me-1"></i> {{ $photo->caption }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Timeline -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-signpost-split-fill text-dlh-primary me-2"></i>Timeline Status</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline">
                                @foreach($report->logs as $log)
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="bi {{ $log->action_icon }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-1">{{ $log->action_label }}</h6>
                                        <p class="text-muted small mb-1">{{ $log->description }}</p>
                                        <span class="badge bg-light text-dark border">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Vertical Timeline CSS */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        position: absolute;
        left: -30px;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
</style>
@endpush
