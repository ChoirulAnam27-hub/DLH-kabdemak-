@extends('layouts.admin')

@section('title', 'Manajemen Laporan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Laporan</h4>
        <span class="text-muted">Kelola, filter, dan tindaklanjuti laporan pengaduan masyarakat.</span>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <!-- Filter Form -->
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row gy-3 gx-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Kecamatan</label>
                <select name="kecamatan_id" id="filterKecamatan" class="form-select">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatans as $kec)
                    <option value="{{ $kec->id }}" {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Desa</label>
                <select name="desa_id" id="filterDesa" class="form-select" {{ request('kecamatan_id') ? '' : 'disabled' }}>
                    <option value="">Semua Desa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Cari (Tiket / Nama)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter Data</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No. Tiket / Tanggal</th>
                        <th>Foto</th>
                        <th>Kategori / Lokasi</th>
                        <th>Pelapor</th>
                        <th>Status / Petugas</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark">{{ $report->ticket_code }}</span>
                            @if($report->is_sla_overdue)
                            <span class="badge bg-danger ms-1 px-2 py-1 align-middle sla-pulse" style="font-size: 0.6rem;">SLA Terlewat</span>
                            @endif
                            <br>
                            <small class="text-muted"><i class="bi bi-calendar2 me-1"></i> {{ $report->created_at->format('d M Y, H:i') }}</small>
                        </td>
                        <td>
                            @if($report->evidencePhotos->count() > 0)
                            <a href="{{ $report->evidencePhotos->first()->photo_url }}" target="_blank">
                                <img src="{{ $report->evidencePhotos->first()->photo_url }}" class="rounded shadow-sm object-fit-cover" width="70" height="50" alt="Foto Laporan">
                            </a>
                            @else
                            <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 50px; font-size: 0.8rem;">
                                <i class="bi bi-image"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill text-white mb-1" style="background-color: {{ $report->category->color }}">
                                <i class="{{ $report->category->icon }} me-1"></i> {{ $report->category->name }}
                            </span><br>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $report->kecamatan }}</small>
                        </td>
                        <td>
                            <span class="fw-medium">{{ $report->display_reporter_name }}</span><br>
                            <small class="text-muted"><i class="bi bi-whatsapp me-1 text-success"></i> {{ $report->display_reporter_phone }}</small>
                        </td>
                        <td>
                            <div class="mb-1">{!! $report->status_badge !!}</div>
                            @if($report->assignedUser)
                                <small class="text-muted"><i class="bi bi-person-badge me-1"></i> {{ $report->assignedUser->name }}</small>
                            @else
                                <small class="text-muted fst-italic">Belum ditugaskan</small>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary mb-1 w-100">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            @if(auth()->user()->role === 'petugas')
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="btn btn-sm btn-dark text-white shadow-sm mb-1 w-100">
                                <i class="bi bi-cursor-fill text-warning"></i> Navigasi
                            </a>
                                @if($report->status === 'diproses')
                                <button type="button" class="btn btn-sm btn-success text-white shadow-sm w-100" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $report->id }}">
                                    <i class="bi bi-camera-fill"></i> Upload Bukti
                                </button>
                                
                                <!-- Modal Upload untuk Petugas -->
                                <div class="modal fade text-start" id="uploadModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-success text-white border-0">
                                                <h6 class="modal-title fw-bold"><i class="bi bi-cloud-arrow-up-fill me-2"></i>Upload Bukti Penyelesaian</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 bg-light">
                                                <div class="mb-3 text-center">
                                                    <span class="badge bg-secondary mb-2">{{ $report->ticket_code }}</span>
                                                    <p class="small text-muted mb-0">{{ $report->address }}</p>
                                                </div>
                                                <form action="{{ route('admin.reports.upload-resolution', $report->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-success">Ambil Foto / Galeri <span class="text-danger">*</span></label>
                                                        <input type="file" name="photo" class="form-control form-control-lg" accept="image/*" capture="environment" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold">Catatan Tindakan</label>
                                                        <textarea name="caption" class="form-control" rows="2" placeholder="Contoh: Sampah sudah diangkut ke TPA..."></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow-sm"><i class="bi bi-upload me-2"></i> KIRIM BUKTI</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">Data laporan tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
    <div class="card-footer bg-white pt-4 pb-2 border-top-0">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dependent Dropdown Filter
        const kecSelect = document.getElementById('filterKecamatan');
        const desaSelect = document.getElementById('filterDesa');
        const oldDesaId = '{{ request('desa_id') }}';

        kecSelect.addEventListener('change', function() {
            const kecId = this.value;
            desaSelect.innerHTML = '<option value="">Semua Desa</option>';
            
            if (kecId) {
                desaSelect.disabled = true;
                fetch(`/api/kecamatan/${kecId}/desas`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(desa => {
                            const selected = oldDesaId == desa.id ? 'selected' : '';
                            desaSelect.innerHTML += `<option value="${desa.id}" ${selected}>${desa.name}</option>`;
                        });
                        desaSelect.disabled = false;
                    });
            } else {
                desaSelect.disabled = true;
            }
        });

        if (kecSelect.value) {
            kecSelect.dispatchEvent(new Event('change'));
        }
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
