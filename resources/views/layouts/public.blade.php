<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan Kabupaten Demak - Dinas Lingkungan Hidup">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pengaduan Lingkungan') — DLH Kabupaten Demak</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Custom CSS --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar navbar-expand-lg navbar-dark navbar-dlh sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-dlh.png') }}" alt="Logo DLH Demak" onerror="this.src='https://ui-avatars.com/api/?name=DLH&background=1B5E20&color=fff&size=42'">
                <div>
                    <div style="font-size: 0.95rem; line-height: 1.2;">Pengaduan Lingkungan</div>
                    <div style="font-size: 0.7rem; opacity: 0.8; font-weight: 400;">DLH Kabupaten Demak</div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('report.create') ? 'active' : '' }}" href="{{ route('report.create') }}">
                            <i class="bi bi-megaphone me-1"></i> Buat Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('report.track*') ? 'active' : '' }}" href="{{ route('report.track') }}">
                            <i class="bi bi-search me-1"></i> Lacak Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-shield-lock me-1"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="footer-dlh">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-white mb-2"><i class="bi bi-building me-2"></i>Dinas Lingkungan Hidup Kab. Demak</h6>
                    <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>Jl. Sultan Fatah No. 1, Demak, Jawa Tengah 59511</p>
                    <p class="mb-0"><i class="bi bi-telephone me-2"></i>(0291) 685123</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p class="mb-1">&copy; {{ date('Y') }} DLH Kabupaten Demak</p>
                    <p class="mb-0" style="font-size: 0.8rem;">Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
