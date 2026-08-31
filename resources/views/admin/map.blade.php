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
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    /* Toggle button for filter panel */
    .map-filter-toggle {
        display: none;
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1001;
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        color: #333;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.2s ease;
        align-items: center;
        justify-content: center;
    }
    .map-filter-toggle:hover {
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .map-filter-toggle.active {
        background: var(--dlh-primary, #198754);
        color: #fff;
        border-color: var(--dlh-primary, #198754);
    }

    @media (max-width: 767.98px) {
        .map-filter-toggle {
            display: flex;
        }
        .map-filter-panel {
            top: 65px;
            right: 12px;
            left: 12px;
            width: auto;
            max-height: calc(100% - 80px);
            overflow-y: auto;
            padding: 16px;
        }
        .map-filter-panel.collapsed {
            transform: translateY(-10px);
            opacity: 0;
            pointer-events: none;
            visibility: hidden;
        }
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

    <!-- Toggle Button for Mobile -->
    <button type="button" class="map-filter-toggle" id="filterToggleBtn" title="Filter Peta">
        <i class="bi bi-funnel-fill" id="filterToggleIcon"></i>
    </button>
    
    <!-- Filter Panel Overlay -->
    <div class="map-filter-panel collapsed" id="mapFilterPanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Peta</h6>
            <button type="button" class="btn-close d-md-none" id="filterCloseBtn" aria-label="Tutup"></button>
        </div>
        
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('filterToggleBtn');
        const toggleIcon = document.getElementById('filterToggleIcon');
        const filterPanel = document.getElementById('mapFilterPanel');
        const closeBtn = document.getElementById('filterCloseBtn');

        function isMobile() {
            return window.innerWidth < 768;
        }

        function toggleFilter() {
            filterPanel.classList.toggle('collapsed');
            toggleBtn.classList.toggle('active');
            if (toggleBtn.classList.contains('active')) {
                toggleIcon.classList.replace('bi-funnel-fill', 'bi-x-lg');
            } else {
                toggleIcon.classList.replace('bi-x-lg', 'bi-funnel-fill');
            }
        }

        function closeFilter() {
            filterPanel.classList.add('collapsed');
            toggleBtn.classList.remove('active');
            toggleIcon.classList.replace('bi-x-lg', 'bi-funnel-fill');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleFilter);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeFilter);
        }

        // On resize: if switching to desktop, make sure panel is visible
        window.addEventListener('resize', function () {
            if (!isMobile()) {
                filterPanel.classList.remove('collapsed');
                toggleBtn.classList.remove('active');
                toggleIcon.classList.replace('bi-x-lg', 'bi-funnel-fill');
            } else {
                // When switching back to mobile, collapse by default
                filterPanel.classList.add('collapsed');
            }
        });

        // Auto-close on mobile after applying filter
        const applyBtn = document.getElementById('btnApplyFilter');
        if (applyBtn) {
            applyBtn.addEventListener('click', function () {
                if (isMobile()) {
                    setTimeout(closeFilter, 300);
                }
            });
        }
    });
</script>
@endpush
