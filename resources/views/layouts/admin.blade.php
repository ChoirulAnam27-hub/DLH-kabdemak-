<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin DLH Demak</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo-dlh.png') }}" alt="Logo DLH" onerror="this.src='https://ui-avatars.com/api/?name=DLH&background=1B5E20&color=fff&size=40'">
            <div>
                <h6>DLH Demak</h6>
                <small>Sistem Pengaduan</small>
            </div>
        </div>

        <nav class="mt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-clipboard-data"></i> Laporan Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.map') ? 'active' : '' }}" href="{{ route('admin.map') }}">
                        <i class="bi bi-geo-alt"></i> Peta Sebaran
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                <li class="nav-item mt-3">
                    <small class="text-white-50 px-3 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Export</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.export.pdf') }}" target="_blank">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.export.excel') }}">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </li>
                @endif

                <li class="nav-item mt-3">
                    <small class="text-white-50 px-3 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Akun</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i> Lihat Situs
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-left"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="admin-content">
        {{-- Top Bar --}}
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success rounded-pill">
                    <i class="bi bi-person me-1"></i>{{ auth()->user()->name }}
                </span>
                <span class="badge bg-secondary rounded-pill text-uppercase" style="font-size: 0.7rem;">
                    {{ auth()->user()->role }}
                </span>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Page Content --}}
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar')?.addEventListener('click', () => {
            document.getElementById('adminSidebar').classList.toggle('show');
        });
    </script>

    @stack('scripts')
</body>
</html>
