<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - DLH Demak</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --dlh-primary: #198754;
            --dlh-dark: #146c43;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: #1e293b;
            color: #fff;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1045;
            overflow-y: auto;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        }
        .sidebar-header {
            padding: 1.25rem 1.5rem;
            background: #0f172a;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        #sidebar ul.components {
            padding: 1rem 0;
        }
        #sidebar ul li a {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #ffffff;
            background: rgba(255,255,255,0.06);
            border-left-color: var(--dlh-primary);
        }
        #sidebar ul li a i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Content */
        #content {
            width: 100%;
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .main-content {
            padding: 1.5rem;
            flex-grow: 1;
        }
        
        /* Card Styling & Hover animations */
        .card { 
            border-radius: 12px; 
            border: none; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); 
            transition: all 0.25s ease;
        }
        .card-header { 
            background: #fff; 
            border-bottom: 1px solid #f0f0f0; 
            border-radius: 12px 12px 0 0 !important; 
        }
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
        }

        /* Responsive Layout Behavior */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #content {
                margin-left: 0 !important;
            }
        }
        @media (min-width: 992px) {
            #sidebar.collapsed {
                transform: translateX(-100%);
            }
            #content.expanded {
                margin-left: 0;
            }
        }
        @media (max-width: 575.98px) {
            .main-content {
                padding: 1rem;
            }
            .topbar {
                padding: 0.75rem 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="wrapper">
        <!-- Backdrop Overlay for Mobile -->
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <img src="{{ asset('assets/images/logo-dlh.png') }}" alt="Logo DLH Demak" class="me-2" style="width: 36px; height: 36px; object-fit: contain;">
                    <h5 class="mb-0 fw-bold">DLH Demak</h5>
                </a>
                <button type="button" class="btn text-white-50 d-lg-none p-0 border-0" id="closeSidebarBtn">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>

            <ul class="list-unstyled components">
                <li class="px-3 mb-2 text-uppercase text-muted small fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Menu Utama</li>
                
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                </li>
                @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.export.pdf') }}" class="text-danger"><i class="bi bi-file-earmark-pdf-fill"></i> Export PDF</a>
                </li>
                <li>
                    <a href="{{ route('admin.export.excel') }}" class="text-success"><i class="bi bi-file-earmark-excel-fill"></i> Export Excel</a>
                </li>
                @endif
                
                @if(auth()->user()->isAdmin())
                <li class="{{ request()->routeIs('admin.reports.index') || (request()->routeIs('admin.reports.show') && !auth()->user()->isPetugas()) ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}"><i class="bi bi-inbox-fill"></i> Manajemen Laporan</a>
                </li>
                @endif
                
                @if(auth()->user()->isPetugas())
                <li class="{{ request()->routeIs('admin.reports.my-tasks', 'admin.reports.show') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.my-tasks') }}"><i class="bi bi-list-check"></i> Tugas Saya</a>
                </li>
                @endif
                
                <li class="{{ request()->routeIs('admin.map') ? 'active' : '' }}">
                    <a href="{{ route('admin.map') }}"><i class="bi bi-map-fill"></i> Peta Sebaran</a>
                </li>
                
                @if(auth()->user()->isAdmin())
                <li class="px-3 mt-4 mb-2 text-uppercase text-muted small fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Administrator</li>
                <li class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.petugas.index') }}"><i class="bi bi-people-fill"></i> Petugas Lapangan</a>
                </li>
                @endif
                
                <li class="px-3 mt-4 mb-2 text-uppercase text-muted small fw-bold" style="font-size: 0.725rem; letter-spacing: 0.5px;">Lainnya</li>
                <li>
                    <a href="{{ route('public.landing') }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Portal Publik</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <!-- Topbar -->
            <div class="topbar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button type="button" id="sidebarCollapse" class="btn btn-light shadow-sm me-3 border">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <span class="fw-bold d-none d-sm-inline-block text-dark">Panel {{ auth()->user()->role_label }}</span>
                </div>

                <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle border-0 d-flex align-items-center p-1" type="button" data-bs-toggle="dropdown">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 38px; height: 38px; font-weight: 600;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-md-block me-2">
                            <span class="d-block fw-bold lh-1 small text-dark">{{ auth()->user()->name }}</span>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->role_label }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                        <li class="px-3 py-2 border-bottom d-md-none">
                            <span class="d-block fw-bold text-dark">{{ auth()->user()->name }}</span>
                            <span class="small text-muted">{{ auth()->user()->role_label }}</span>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapse');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('expanded');
                }
            }

            function closeMobileSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }

            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', toggleSidebar);
            }

            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', closeMobileSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeMobileSidebar);
            }

            // Auto close mobile sidebar when window is resized to desktop width
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
