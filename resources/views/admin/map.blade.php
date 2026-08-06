@extends('layouts.admin')

@section('title', 'Peta Sebaran Laporan')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<style>
    .map-container { position: relative; height: calc(100vh - 120px); min-height: 500px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    #adminMap { width: 100%; height: 100%; z-index: 1; }
    
    /* Overlay Filter Panel */
    .map-filter-panel {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 12px;
        width: 300px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Custom Map Marker */
    .custom-div-icon {
        background: transparent;
        border: none;
    }
    .marker-pin {
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        background: #dc3545;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin: -17px 0 0 -17px;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
    }
    .marker-pin::after {
        content: '';
        width: 24px;
        height: 24px;
        margin: 5px 0 0 5px;
        background: #fff;
        position: absolute;
        border-radius: 50%;
    }
    .custom-div-icon i {
        position: absolute;
        width: 22px;
        font-size: 14px;
        left: 0;
        right: 0;
        margin: 10px auto;
        text-align: center;
        color: #333;
        z-index: 2;
    }
    
    /* Status Colors */
    .pin-pending { background: #ffc107; } /* Warning */
    .pin-diproses { background: #0dcaf0; } /* Info */
    .pin-selesai { background: #198754; } /* Success */
    .pin-ditolak { background: #dc3545; } /* Danger */
</style>
@endpush

@section('content')
<div class="map-container">
    <div id="adminMap"></div>
    
    <!-- Filter Panel Overlay -->
    <div class="map-filter-panel">
        <h6 class="fw-bold mb-3"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Peta</h6>
        
        <form id="mapFilterForm">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted">Status Penanganan</label>
                <select name="status" id="filterStatus" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-muted">Kategori Masalah</label>
                <select name="category_id" id="filterCategory" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="d-grid">
                <button type="button" id="btnApplyFilter" class="btn btn-sm btn-primary fw-bold">Terapkan Filter</button>
            </div>
        </form>

        <hr class="my-3">
        
        <div class="small">
            <div class="fw-bold mb-2">Legenda Status:</div>
            <div class="d-flex align-items-center mb-1"><span class="d-inline-block rounded-circle bg-warning me-2" style="width: 12px; height: 12px;"></span> Pending</div>
            <div class="d-flex align-items-center mb-1"><span class="d-inline-block rounded-circle bg-info me-2" style="width: 12px; height: 12px;"></span> Diproses</div>
            <div class="d-flex align-items-center mb-1"><span class="d-inline-block rounded-circle bg-success me-2" style="width: 12px; height: 12px;"></span> Selesai</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pass API URL to external JS file
    const mapDataUrl = "{{ route('api.map-data') }}";
</script>
<!-- Leaflet & MarkerCluster JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<!-- Custom Map Logic -->
<script src="{{ asset('js/leaflet-admin-map.js') }}"></script>
@endpush
