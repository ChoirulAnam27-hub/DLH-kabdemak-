@extends('layouts.public')

@section('title', 'Lacak Laporan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Lacak Status Laporan</h2>
                <p class="text-muted">Pantau sejauh mana laporan Anda ditangani oleh tim kami.</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-search me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <div class="card-body p-5">
                    <form action="{{ route('public.track.search') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Masukkan Kode Tiket atau Nomor WhatsApp</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" name="search_key" class="form-control border-start-0 ps-0" placeholder="Contoh: DLH-20240101-001 atau 08123456..." required value="{{ old('search_key') }}">
                                <button class="btn btn-dlh-primary px-4" type="submit">Cari Laporan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row text-center gy-4 mt-2">
                <div class="col-md-4">
                    <div class="text-warning mb-2"><i class="bi bi-clock-history fs-1"></i></div>
                    <h5 class="fw-bold">1. Menunggu</h5>
                    <p class="text-muted small">Laporan sedang diverifikasi oleh admin.</p>
                </div>
                <div class="col-md-4">
                    <div class="text-info mb-2"><i class="bi bi-gear-fill fs-1"></i></div>
                    <h5 class="fw-bold">2. Diproses</h5>
                    <p class="text-muted small">Petugas sedang menuju ke lokasi / menangani.</p>
                </div>
                <div class="col-md-4">
                    <div class="text-success mb-2"><i class="bi bi-check-circle-fill fs-1"></i></div>
                    <h5 class="fw-bold">3. Selesai</h5>
                    <p class="text-muted small">Penanganan selesai, dilampirkan foto bukti.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
