<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pengaduan') - DLH Demak</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --dlh-primary: #198754; /* Bootstrap success green */
            --dlh-dark: #146c43;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--dlh-primary) !important;
        }
        .bg-dlh-primary {
            background-color: var(--dlh-primary) !important;
        }
        .text-dlh-primary {
            color: var(--dlh-primary) !important;
        }
        .btn-dlh-primary {
            background-color: var(--dlh-primary);
            color: white;
            border: none;
        }
        .btn-dlh-primary:hover {
            background-color: var(--dlh-dark);
            color: white;
        }
        main {
            flex: 1;
        }
        .footer {
            background-color: #1a1d20;
            color: #adb5bd;
            padding: 4rem 0 2rem;
            margin-top: auto;
        }
        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgba(25, 135, 84, 0.1);
            color: var(--dlh-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 15px;
        }
        .hover-lift {
            transition: transform 0.2s ease, opacity 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            opacity: 1 !important;
        }
        .link-hover-shift {
            transition: all 0.2s ease;
        }
        .link-hover-shift:hover {
            color: #ffffff !important;
            opacity: 1 !important;
            padding-left: 4px;
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.landing') }}">
                <i class="bi bi-tree-fill me-2 fs-3 text-dlh-primary"></i>
                <div>
                    <div class="lh-1 fs-5">DLH Kabupaten Demak</div>
                    <div class="lh-1 fs-6 text-muted fw-normal mt-1" style="font-size: 0.8rem !important;">Sistem Pengaduan Lingkungan</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.landing') ? 'active fw-bold' : '' }}" href="{{ route('public.landing') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.report.create') ? 'active fw-bold' : '' }}" href="{{ route('public.report.create') }}">Buat Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.track.*') ? 'active fw-bold' : '' }}" href="{{ route('public.track.index') }}">Lacak Laporan</a>
                    </li>
                    @auth
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    </li>
                    @else
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Login Petugas</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-5 pe-lg-5">
                    <a href="{{ route('public.landing') }}" class="text-decoration-none d-inline-flex align-items-center mb-4">
                        <div class="bg-dlh-primary text-white rounded p-2 me-3 shadow-sm">
                            <i class="bi bi-tree-fill fs-4"></i>
                        </div>
                        <h4 class="text-white mb-0 fw-bold">DLH Demak</h4>
                    </a>
                    <p class="text-light opacity-75 mb-4 lh-lg">Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan Kabupaten Demak. Bersama kita jaga lingkungan agar tetap bersih, asri, dan sehat untuk generasi mendatang.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light opacity-50 hover-lift text-decoration-none"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-light opacity-50 hover-lift text-decoration-none"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-light opacity-50 hover-lift text-decoration-none"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="text-light opacity-50 hover-lift text-decoration-none"><i class="bi bi-youtube fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <h5 class="text-white mb-4 fw-bold">Tautan Cepat</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li><a href="{{ route('public.landing') }}" class="text-decoration-none text-light opacity-75 link-hover-shift d-inline-block"><i class="bi bi-chevron-right small me-2 text-dlh-primary"></i> Beranda</a></li>
                        <li><a href="{{ route('public.report.create') }}" class="text-decoration-none text-light opacity-75 link-hover-shift d-inline-block"><i class="bi bi-chevron-right small me-2 text-dlh-primary"></i> Buat Laporan Baru</a></li>
                        <li><a href="{{ route('public.track.index') }}" class="text-decoration-none text-light opacity-75 link-hover-shift d-inline-block"><i class="bi bi-chevron-right small me-2 text-dlh-primary"></i> Lacak Status Laporan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="text-white mb-4 fw-bold">Kontak Kami</h5>
                    <ul class="list-unstyled text-light opacity-75 d-flex flex-column gap-3 mb-0">
                        <li class="d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-dlh-primary me-3 mt-1"></i>
                            <span class="lh-base">Jl. Sultan Fatah No. 34,<br>Kabupaten Demak, Jawa Tengah</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-dlh-primary me-3"></i>
                            <span>(0291) 123456</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-envelope-fill text-dlh-primary me-3"></i>
                            <span>info@dlh-demak.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="mt-5 mb-4 border-secondary opacity-25">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-light opacity-50 small">
                <div class="mb-2 mb-md-0">
                    &copy; {{ date('Y') }} Dinas Lingkungan Hidup Kabupaten Demak. Hak Cipta Dilindungi.
                </div>
                <div>
                    Dikelola oleh Pemerintah Kabupaten Demak
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
