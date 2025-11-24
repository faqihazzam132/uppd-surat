<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat UPPD Kalideres</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #343a40; color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 15px; }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; }
        .content { padding: 20px; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar (Hanya tampil jika login) -->
    @auth
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-center mb-4">UPPD Kalideres</h4>
        
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>

        <!-- Tombol Logout -->
        <hr>
        <div class="px-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                </button>
            </form>
        </div>

        <!-- Menu Internal (Admin/Staff) -->
        @if(auth()->user()->role != 'pemohon')
            <hr>
            <small class="text-muted ms-3">ADMINISTRASI</small>
            <a href="{{ route('surat-masuk.index') }}" class="{{ request()->is('surat-masuk*') ? 'active' : '' }}">
                <i class="fas fa-inbox me-2"></i> Surat Masuk
            </a>
            <a href="{{ route('disposisi.index') }}" class="{{ request()->is('disposisi*') ? 'active' : '' }}">
                <i class="fas fa-share-square me-2"></i> Disposisi
            </a>
            <a href="{{ route('surat-keluar.index') }}" class="{{ request()->is('surat-keluar*') ? 'active' : '' }}">
                <i class="fas fa-paper-plane me-2"></i> Surat Keluar
            </a>
            <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->is('admin/pengajuan*') ? 'active' : '' }}">
                <i class="fas fa-file-alt me-2"></i> Verifikasi Pengajuan
            </a>
        @endif

        <!-- Menu Pemohon -->
        @if(auth()->user()->role == 'pemohon')
            <hr>
            <small class="text-muted ms-3">LAYANAN</small>
            <a href="{{ route('pengajuan.create') }}" class="{{ request()->is('pengajuan/create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle me-2"></i> Buat Permohonan
            </a>
            <a href="{{ route('pengajuan.index') }}" class="{{ request()->is('pengajuan') ? 'active' : '' }}">
                <i class="fas fa-history me-2"></i> Riwayat Saya
            </a>
        @endif

        <hr>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
    @endauth

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Navbar Mobile (Opsional) -->
        <nav class="navbar navbar-light bg-white shadow-sm mb-4 d-md-none">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">UPPD Surat</span>
            </div>
        </nav>

        <div class="content">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>