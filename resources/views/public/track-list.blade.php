@extends('layouts.public')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0">Hasil Pencarian untuk: <span class="text-dlh-primary">"{{ $key }}"</span></h3>
                <a href="{{ route('public.track.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>

            <p class="text-muted mb-4">Ditemukan {{ $reports->count() }} laporan yang sesuai.</p>

            <div class="row gy-3">
                @foreach($reports as $report)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 hover-shadow transition-all">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-dark rounded-pill me-2">{{ $report->ticket_code }}</span>
                                        {!! $report->status_badge !!}
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $report->category->name }}</h5>
                                    <p class="text-muted small mb-0"><i class="bi bi-calendar-event me-1"></i> {{ $report->created_at->translatedFormat('d F Y, H:i') }} | <i class="bi bi-geo-alt me-1"></i> {{ $report->kecamatan }}</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <a href="{{ route('public.track.show', $report->ticket_code) }}" class="btn btn-outline-primary rounded-pill px-4">
                                        Lihat Detail <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
        transform: translateY(-2px);
    }
    .transition-all { transition: all 0.3s ease; }
</style>
@endpush
