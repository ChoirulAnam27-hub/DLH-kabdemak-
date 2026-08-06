@extends('layouts.public')

@section('title', 'Buat Laporan Baru')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 400px; z-index: 1; border-radius: 8px; }
    .upload-box {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .upload-box:hover {
        border-color: var(--dlh-primary);
        background: rgba(25, 135, 84, 0.05);
    }
    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    .preview-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        position: relative;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Buat Laporan Baru</h2>
                <p class="text-muted">Isi formulir di bawah ini dengan data yang valid agar laporan Anda dapat segera ditindaklanjuti.</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('public.report.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
                @csrf
                
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-person-circle text-dlh-primary me-2"></i> Data Pelapor</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="isAnonymous" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small fw-semibold" for="isAnonymous">Laporkan secara Anonim</label>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger required-star">*</span></label>
                                <input type="text" name="reporter_name" id="reporterName" class="form-control @error('reporter_name') is-invalid @enderror" value="{{ old('reporter_name') }}" required placeholder="Contoh: Budi Santoso">
                                @error('reporter_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger required-star">*</span></label>
                                <input type="text" name="reporter_phone" id="reporterPhone" class="form-control @error('reporter_phone') is-invalid @enderror" value="{{ old('reporter_phone') }}" required placeholder="Contoh: 081234567890">
                                <div class="form-text">Gunakan nomor WA aktif untuk pengecekan status.</div>
                                @error('reporter_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold"><i class="bi bi-file-earmark-text-fill text-dlh-primary me-2"></i> Detail Laporan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Kategori Pengaduan <span class="text-danger">*</span></label>
                            <div class="row gy-3">
                                @foreach($categories as $cat)
                                <div class="col-md-4 col-sm-6">
                                    <input type="radio" class="btn-check" name="category_id" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-secondary w-100 text-start d-flex align-items-center" for="cat_{{ $cat->id }}" style="height: 100%;">
                                        <i class="{{ $cat->icon }} fs-4 me-3" style="color: {{ $cat->color }}"></i>
                                        <span>{{ $cat->name }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('category_id') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deskripsi Kejadian <span class="text-danger">*</span></label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required placeholder="Jelaskan secara detail apa yang terjadi, sejak kapan, dan dampaknya...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Bukti (Maks. 3 foto) <span class="text-danger">*</span></label>
                            <input type="file" name="photos[]" id="photoInput" class="d-none" accept="image/jpeg,image/png,image/jpg" capture="environment" multiple>
                            <div class="upload-box" onclick="document.getElementById('photoInput').click()">
                                <i class="bi bi-cloud-arrow-up text-dlh-primary" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Klik untuk Memilih Foto</h6>
                                <p class="text-muted small mb-0">Format: JPG, JPEG, PNG. Maksimal 5MB per foto.</p>
                            </div>
                            <div id="photoPreviewContainer" class="preview-container"></div>
                            @error('photos.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold"><i class="bi bi-geo-alt-fill text-dlh-primary me-2"></i> Lokasi Kejadian</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <!-- Duplicate Alert Container -->
                                <div id="duplicateAlert" class="alert alert-warning d-none" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Perhatian!</strong> Terdapat laporan serupa di sekitar lokasi ini yang sedang diproses. Anda tetap dapat melanjutkan, namun laporan Anda mungkin dianggap sebagai duplikat.
                                </div>
                                <button type="button" id="btnGetCurrentLocation" class="btn btn-outline-primary mb-3">
                                    <i class="bi bi-crosshair me-2"></i> Deteksi Lokasi Saya Saat Ini
                                </button>
                                <p class="text-muted small mb-2"><i class="bi bi-info-circle me-1"></i> Geser pin merah (marker) ke lokasi pasti kejadian pada peta.</p>
                                <div id="map" class="border rounded"></div>
                            </div>
                        </div>

                        <!-- Hidden Inputs for Lat Lng -->
                        <input type="hidden" name="latitude" id="inputLatitude" value="{{ old('latitude', '-6.8936') }}">
                        <input type="hidden" name="longitude" id="inputLongitude" value="{{ old('longitude', '110.6382') }}">
                        @error('latitude') <div class="text-danger small mt-2">Koordinat lokasi wajib diisi via peta.</div> @enderror

                        <div class="row gy-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                                <select name="kecamatan_id" id="kecamatanSelect" class="form-select @error('kecamatan_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kecamatan...</option>
                                    @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->name }}</option>
                                    @endforeach
                                </select>
                                @error('kecamatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Desa/Kelurahan</label>
                                <select name="desa_id" id="desaSelect" class="form-select @error('desa_id') is-invalid @enderror">
                                    <option value="">Pilih Desa/Kelurahan...</option>
                                </select>
                                <div id="desaLoader" class="spinner-border spinner-border-sm text-primary d-none mt-2" role="status"></div>
                                @error('desa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Detail <span class="text-danger">*</span></label>
                                <textarea name="address" id="inputAddress" rows="2" class="form-control @error('address') is-invalid @enderror" required placeholder="Contoh: Jl. Sultan Fatah No. 1, samping minimarket X...">{{ old('address') }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 mb-5">
                    <button type="submit" class="btn btn-dlh-primary btn-lg px-5 rounded-pill shadow-sm" id="btnSubmit">
                        <i class="bi bi-send-fill me-2"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Custom JS -->
<script src="{{ asset('js/leaflet-report.js') }}"></script>
<script src="{{ asset('js/photo-upload.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Anonymous
        const cbAnonymous = document.getElementById('isAnonymous');
        const inputName = document.getElementById('reporterName');
        const inputPhone = document.getElementById('reporterPhone');
        const reqStars = document.querySelectorAll('.required-star');
        
        function toggleAnonymous() {
            const isAnon = cbAnonymous.checked;
            inputName.disabled = isAnon;
            inputPhone.disabled = isAnon;
            
            if (isAnon) {
                inputName.removeAttribute('required');
                inputPhone.removeAttribute('required');
                reqStars.forEach(s => s.classList.add('d-none'));
            } else {
                inputName.setAttribute('required', 'required');
                inputPhone.setAttribute('required', 'required');
                reqStars.forEach(s => s.classList.remove('d-none'));
            }
        }
        
        cbAnonymous.addEventListener('change', toggleAnonymous);
        toggleAnonymous(); // Init state

        // Dependent Dropdown Kecamatan -> Desa
        const kecSelect = document.getElementById('kecamatanSelect');
        const desaSelect = document.getElementById('desaSelect');
        const desaLoader = document.getElementById('desaLoader');
        
        const oldDesaId = '{{ old('desa_id') }}';

        kecSelect.addEventListener('change', function() {
            const kecId = this.value;
            desaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan...</option>';
            
            if (kecId) {
                desaLoader.classList.remove('d-none');
                desaSelect.disabled = true;
                
                fetch(`/api/kecamatan/${kecId}/desas`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(desa => {
                            const selected = oldDesaId == desa.id ? 'selected' : '';
                            desaSelect.innerHTML += `<option value="${desa.id}" ${selected}>${desa.name}</option>`;
                        });
                        desaLoader.classList.add('d-none');
                        desaSelect.disabled = false;
                    })
                    .catch(err => {
                        console.error('Error fetching desas:', err);
                        desaLoader.classList.add('d-none');
                        desaSelect.disabled = false;
                    });
            }
        });

        // Trigger change if old kecamatan_id exists (e.g. back from validation error)
        if (kecSelect.value) {
            kecSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
