@extends('layouts.admin')

@section('title', 'Detail Laporan: ' . $report->ticket_code)

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 250px; border-radius: 8px; z-index: 1;}
    .timeline { position: relative; padding-left: 30px; }
    .timeline::before { content: ''; position: absolute; left: 11px; top: 0; bottom: 0; width: 2px; background: #e9ecef; }
    .timeline-item { position: relative; margin-bottom: 20px; }
    .timeline-icon { position: absolute; left: -30px; top: 0; width: 24px; height: 24px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .timeline-content { background: #f8f9fa; padding: 12px; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Laporan</a>
        <h4 class="fw-bold mb-0">Tiket: <span class="text-primary">{{ $report->ticket_code }}</span></h4>
    </div>
    <div>
        {!! $report->status_badge !!} {!! $report->priority_badge !!}
    </div>
</div>

<div class="row gy-4">
    <div class="col-lg-8">
        <!-- Informasi Utama -->
        <div class="card mb-4">
            <div class="card-header pt-4 pb-0 px-4 border-0">
                <h6 class="fw-bold"><i class="bi bi-info-circle-fill text-primary me-2"></i>Informasi Laporan</h6>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Kategori</div>
                    <div class="col-sm-8">
                        <span class="badge rounded-pill text-white" style="background-color: {{ $report->category->color }}">
                            <i class="{{ $report->category->icon }} me-1"></i> {{ $report->category->name }}
                        </span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Waktu Dilaporkan</div>
                    <div class="col-sm-8">{{ $report->created_at->translatedFormat('l, d F Y - H:i') }} WIB</div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Pelapor</div>
                    <div class="col-sm-8">
                        <strong>{{ $report->display_reporter_name }}</strong><br>
                        <a href="https://wa.me/{{ $report->display_reporter_phone }}" target="_blank" class="text-decoration-none text-success">
                            <i class="bi bi-whatsapp"></i> {{ $report->display_reporter_phone }}
                        </a>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Deskripsi</div>
                    <div class="col-sm-8">
                        <p class="mb-0 bg-light p-3 rounded">{{ $report->description }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Lokasi (Alamat)</div>
                    <div class="col-sm-8">
                        <p class="mb-1">{{ $report->address }}</p>
                        <p class="text-muted small">Kel. {{ $report->kelurahan ?? '-' }}, Kec. {{ $report->kecamatan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peta Lokasi -->
        <div class="card mb-4">
            <div class="card-header pt-4 pb-0 px-4 border-0">
                <h6 class="fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Titik Koordinat (GPS)</h6>
            </div>
            <div class="card-body p-4">
                <div id="map" class="mb-3"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <code class="text-dark bg-light px-2 py-1 rounded">{{ $report->latitude }}, {{ $report->longitude }}</code>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">Buka di Google Maps</a>
                </div>
            </div>
        </div>

        <!-- Foto Bukti & Penyelesaian -->
        <div class="row">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header pt-4 pb-0 px-4 border-0">
                        <h6 class="fw-bold"><i class="bi bi-camera-fill text-warning me-2"></i>Foto Bukti Warga</h6>
                    </div>
                    <div class="card-body p-4">
                        @if($report->evidencePhotos->count() > 0)
                        <div class="row g-2">
                            @foreach($report->evidencePhotos as $photo)
                            <div class="col-6">
                                <a href="{{ $photo->photo_url }}" target="_blank">
                                    <img src="{{ $photo->photo_url }}" class="img-fluid rounded border" alt="Bukti Foto" style="height: 100px; width:100%; object-fit: cover;">
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted fst-italic small">Tidak ada lampiran foto.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100 border-success">
                    <div class="card-header pt-4 pb-0 px-4 border-0 bg-transparent">
                        <h6 class="fw-bold text-success"><i class="bi bi-check-circle-fill me-2"></i>Foto Penyelesaian</h6>
                    </div>
                    <div class="card-body p-4">
                        @if($report->resolutionPhotos->count() > 0)
                        <div class="row g-2">
                            @foreach($report->resolutionPhotos as $photo)
                            <div class="col-6">
                                <a href="{{ $photo->photo_url }}" target="_blank">
                                    <img src="{{ $photo->photo_url }}" class="img-fluid rounded border border-success" alt="Foto Selesai" style="height: 100px; width:100%; object-fit: cover;">
                                </a>
                                @if($photo->caption)
                                <p class="text-muted small mt-1 mb-0"><i class="bi bi-quote"></i> {{ $photo->caption }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted fst-italic small mb-3">Belum ada foto bukti penyelesaian kerja dari petugas lapangan.</p>
                        
                        <!-- Form Upload (Hanya tampil jika status diproses) -->
                        @if($report->status === 'diproses' && (auth()->user()->isAdmin() || auth()->id() === $report->assigned_to))
                        <form action="{{ route('admin.reports.upload-resolution', $report->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photo" class="form-control form-control-sm mb-2" accept="image/*" required>
                            <input type="text" name="caption" class="form-control form-control-sm mb-2" placeholder="Catatan opsional...">
                            <button type="submit" class="btn btn-sm btn-success w-100"><i class="bi bi-upload me-1"></i> Upload Foto Penyelesaian</button>
                        </form>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Form Aksi Disposisi / Ubah Status -->
        <div class="card mb-4 bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-gear-fill text-primary me-2"></i>Tindakan (Action)</h6>
                
                <!-- Disposisi (Hanya Admin) -->
                @if(auth()->user()->isAdmin())
                <form action="{{ route('admin.reports.assign', $report->id) }}" method="POST" class="mb-4 pb-3 border-bottom border-secondary">
                    @csrf
                    @method('PUT')
                    <label class="form-label small fw-bold">Disposisi ke Petugas</label>
                    <div class="input-group input-group-sm">
                        <select name="petugas_id" class="form-select" required>
                            <option value="">Pilih Petugas...</option>
                            @foreach($petugas as $p)
                            <option value="{{ $p->id }}" {{ $report->assigned_to == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->assigned_reports_count }} Tugas Aktif)</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Tugaskan</button>
                    </div>
                    @if($report->assignedUser)
                    <div class="mt-2 small text-muted"><i class="bi bi-check2-circle text-success me-1"></i> Saat ini ditugaskan ke: <strong>{{ $report->assignedUser->name }}</strong></div>
                    @endif
                </form>
                @endif

                <!-- Update Status -->
                <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="form-label small fw-bold">Ubah Status Laporan</label>
                    <select name="status" id="statusSelect" class="form-select mb-2" required>
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses (Sedang Ditangani)</option>
                        <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai (Tuntas)</option>
                        <option value="ditolak" {{ $report->status == 'ditolak' ? 'selected' : '' }}>Ditolak (Tidak Valid/Bukan Wewenang)</option>
                    </select>
                    
                    <div id="rejectionReasonContainer" class="mb-2 d-none">
                        <label class="form-label small fw-bold text-danger">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejectionReason" class="form-control form-control-sm" rows="2" placeholder="Wajib diisi jika ditolak...">{{ $report->rejection_reason }}</textarea>
                    </div>

                    <textarea name="notes" class="form-control form-control-sm mb-3" rows="2" placeholder="Catatan internal perubahan status (Opsional)..."></textarea>
                    
                    @if($report->status !== 'selesai' && $report->status !== 'ditolak')
                    <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Status</button>
                    @else
                    <button type="submit" class="btn btn-secondary w-100" onclick="return confirm('Laporan sudah selesai/ditolak. Yakin ingin mengubah statusnya lagi?')">Revisi Status</button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Log Aktivitas -->
        <div class="card">
            <div class="card-header pt-4 pb-0 px-4 border-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-secondary me-2"></i>Log Aktivitas (Audit Trail)</h6>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    @foreach($report->logs as $log)
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi {{ $log->action_icon }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1 small">{{ $log->action_label }}</h6>
                            <p class="text-muted mb-1" style="font-size: 0.8rem;">{{ $log->description }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-white text-secondary border" style="font-size: 0.7rem;">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                @if($log->user)
                                <span class="text-muted fst-italic" style="font-size: 0.7rem;">Oleh: {{ $log->user->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = {{ $report->latitude }};
    const lng = {{ $report->longitude }};
    const title = "{{ $report->category->name }}";
    
    // Init map
    const map = L.map('map').setView([lat, lng], 15);

    // Add Tile Layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add Marker
    L.marker([lat, lng]).addTo(map)
        .bindPopup(`<b>${title}</b><br>{{ $report->address }}`)
        .openPopup();

    // Rejection Reason Toggle
    const statusSelect = document.getElementById('statusSelect');
    const rejectionContainer = document.getElementById('rejectionReasonContainer');
    const rejectionInput = document.getElementById('rejectionReason');

    if (statusSelect && rejectionContainer) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'ditolak') {
                rejectionContainer.classList.remove('d-none');
                rejectionInput.setAttribute('required', 'required');
            } else {
                rejectionContainer.classList.add('d-none');
                rejectionInput.removeAttribute('required');
            }
        });
        statusSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
