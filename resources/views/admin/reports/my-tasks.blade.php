@extends('layouts.admin')

@section('title', 'Tugas Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-list-check text-success me-2"></i>Tugas Saya</h4>
        <span class="text-muted">Daftar laporan yang ditugaskan kepada Anda.</span>
    </div>
</div>

<!-- Filter Mobile Friendly -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form action="{{ route('admin.reports.my-tasks') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-lg" onchange="this.form.submit()">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Tugas Aktif</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </form>
    </div>
</div>

<!-- Daftar Tugas (Card Based untuk Mobile) -->
<div class="row g-3">
    @forelse($reports as $report)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100 border-0 shadow-sm {{ $report->status == 'diproses' ? 'border-start border-4 border-primary' : ($report->status == 'selesai' ? 'border-start border-4 border-success' : '') }}">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-light text-dark border mb-2">{{ $report->ticket_code }}</span>
                        <h6 class="fw-bold mb-0">{{ $report->category->name }}</h6>
                    </div>
                    {!! $report->status_badge !!}
                </div>
                
                <p class="text-muted small mb-3">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $report->address }}, Kec. {{ $report->kecamatan }}
                </p>

                @if($report->status === 'diproses')
                    <div class="alert alert-info py-2 px-3 small border-0 mb-4">
                        <i class="bi bi-info-circle-fill me-1"></i> Tugas ini sedang Anda kerjakan. Segera upload foto bukti penyelesaian.
                    </div>
                @elseif($report->status === 'pending')
                    <div class="alert alert-warning py-2 px-3 small border-0 mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Buka detail untuk mulai memproses tugas ini.
                    </div>
                @endif

                <div class="d-grid gap-2 mt-auto">
                    <!-- Tombol Upload Langsung (Hanya jika diproses) -->
                    @if($report->status === 'diproses')
                        <button type="button" class="btn btn-lg btn-success fw-bold text-uppercase py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $report->id }}">
                            <i class="bi bi-camera-fill me-2 fs-5"></i> Upload Bukti
                        </button>
                        
                        <!-- Modal Upload -->
                        <div class="modal fade" id="uploadModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-success text-white border-0">
                                        <h6 class="modal-title fw-bold"><i class="bi bi-cloud-arrow-up-fill me-2"></i>Upload Bukti Penyelesaian</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 bg-light">
                                        <form action="{{ route('admin.reports.upload-resolution', $report->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-success">Ambil Foto / Buka Galeri <span class="text-danger">*</span></label>
                                                <input type="file" name="photo" class="form-control form-control-lg" accept="image/*" capture="environment" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">Catatan Tindakan (Opsional)</label>
                                                <textarea name="caption" class="form-control form-control-lg" rows="2" placeholder="Contoh: Sampah sudah diangkut ke TPA..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow-sm">
                                                <i class="bi bi-upload me-2"></i> KIRIM BUKTI
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tombol Detail / Navigasi -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-outline-primary py-2 w-100">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="btn btn-dark text-white py-2 w-100">
                            <i class="bi bi-cursor-fill text-warning"></i> Navigasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="bi bi-check2-circle text-success" style="font-size: 4rem;"></i>
                <h5 class="fw-bold mt-3">Tidak Ada Tugas Aktif</h5>
                <p class="text-muted">Anda tidak memiliki tugas yang perlu diselesaikan saat ini. Kerjaan bagus!</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($reports->hasPages())
<div class="mt-4">
    {{ $reports->links() }}
</div>
@endif
@endsection
