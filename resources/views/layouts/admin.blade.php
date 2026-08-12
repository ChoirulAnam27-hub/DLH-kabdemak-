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
            --sidebar-width: 250px;
            --dlh-primary: #198754;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f8fa;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        /* Sidebar */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #212529;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        #sidebar.active {
            margin-left: calc(var(--sidebar-width) * -1);
        }
        .sidebar-header {
            padding: 20px;
            background: #1a1e21;
            border-bottom: 1px solid #343a40;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.05rem;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            transition: 0.2s;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: rgba(255,255,255,0.05);
            border-left: 4px solid var(--dlh-primary);
        }
        #sidebar ul li a i {
            margin-right: 10px;
        }
        /* Content */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 30px;
        }
        .main-content {
            padding: 30px;
            flex-grow: 1;
        }
        /* Utils */
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; border-radius: 10px 10px 0 0 !important; }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            #sidebar.active {
                margin-left: 0;
            }
            #sidebarCollapse span {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center">
                <i class="bi bi-tree-fill text-success fs-3 me-2"></i>
                <h5 class="mb-0 fw-bold">DLH Demak</h5>
            </div>

            <ul class="list-unstyled components">
                <li class="px-3 mb-2 text-uppercase text-muted small fw-bold" style="font-size: 0.75rem;">Menu Utama</li>
                
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
                <li class="px-3 mt-4 mb-2 text-uppercase text-muted small fw-bold" style="font-size: 0.75rem;">Administrator</li>
                <li class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.petugas.index') }}"><i class="bi bi-people-fill"></i> Petugas Lapangan</a>
                </li>
                @endif
                
                <li class="px-3 mt-4 mb-2 text-uppercase text-muted small fw-bold" style="font-size: 0.75rem;">Lainnya</li>
                <li>
                    <a href="{{ route('public.landing') }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Buka Portal Publik</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <!-- Topbar -->
            <div class="topbar d-flex justify-content-between align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-light shadow-sm">
                    <i class="bi bi-list"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle border-0 d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="text-start d-none d-md-block">
                            <span class="d-block fw-bold lh-1">{{ auth()->user()->name }}</span>
                            <span class="small text-muted">{{ auth()->user()->role_label }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
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
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
    @stack('scripts')
</body>
</html>
