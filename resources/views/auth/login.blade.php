<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Backoffice DLH Demak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .login-side {
            background: linear-gradient(135deg, #198754, #146c43);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }
        .login-form-side {
            padding: 3rem;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }
        .btn-login {
            background-color: #198754;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #146c43;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="card login-card">
                    <div class="row g-0">
                        <div class="col-md-5 d-none d-md-flex login-side">
                            <h2 class="fw-bold mb-4"><i class="bi bi-tree-fill me-2"></i>DLH Demak</h2>
                            <p class="lead mb-4">Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan.</p>
                            <div class="mt-auto">
                                <a href="{{ route('public.landing') }}" class="btn btn-outline-light rounded-pill"><i class="bi bi-arrow-left me-2"></i>Ke Portal Publik</a>
                            </div>
                        </div>
                        <div class="col-md-7 login-form-side">
                            <div class="text-center mb-4 d-md-none">
                                <h3 class="fw-bold text-success"><i class="bi bi-tree-fill me-2"></i>DLH Demak</h3>
                            </div>
                            
                            <h4 class="fw-bold mb-1">Selamat Datang Kembali</h4>
                            <p class="text-muted mb-4">Silakan login menggunakan akun staf Anda.</p>

                            @if($errors->any())
                            <div class="alert alert-danger rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email') }}" required autofocus placeholder="admin@dinlh.demakkab.go.id">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0 ps-0" required placeholder="••••••••">
                                    </div>
                                </div>

                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">Ingat Saya</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-login w-100 mb-3">Login ke Dashboard</button>
                                
                                <div class="text-center d-md-none mt-4">
                                    <a href="{{ route('public.landing') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Ke Portal Publik</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
