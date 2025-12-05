<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .sidebar small.text-muted { color: #ffc107 !important; }
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
            <a href="{{ route('arsip.index') }}" class="{{ request()->is('arsip*') ? 'active' : '' }}">
                <i class="fas fa-archive me-2"></i> Arsip Surat
            </a>
            @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Manajemen Pengguna
                </a>
            @endif
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
        <small class="text-muted ms-3">PENGATURAN</small>
        <a href="{{ route('password.change') }}" class="{{ request()->is('change-password') ? 'active' : '' }}">
            <i class="fas fa-key me-2"></i> Ubah Password
        </a>

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

        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid">
                <span class="navbar-brand">UPPD Kalideres</span>
                
                <!-- Notification Dropdown -->
                <div class="dropdown ms-auto me-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 350px;">
                        <h6 class="dropdown-header">Notifikasi</h6>
                        @forelse(auth()->user() ? auth()->user()->unreadNotifications->take(5) : [] as $notification)
                            <a class="dropdown-item" href="{{ route('notifications.read', $notification->id) }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <div class="me-2" style="white-space: normal;">
                                        <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notifikasi baru' }}</h6>
                                        @if(!empty($notification->data['no_registrasi']))
                                            <small class="text-muted">No. Registrasi: {{ $notification->data['no_registrasi'] }}</small>
                                        @endif
                                    </div>
                                    <small class="text-muted text-nowrap">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <small class="text-muted">Klik untuk melihat detail</small>
                            </a>
                            <div class="dropdown-divider"></div>
                        @empty
                            <a class="dropdown-item text-muted" href="#">Tidak ada notifikasi baru</a>
                        @endforelse
                        @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                            <div class="text-center mt-2">
                                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">Lihat Semua</a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('password.change') }}"><i class="fas fa-key me-2"></i>Ubah Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    @if(session('no_registrasi'))
                        <div class="mt-2">
                            <strong>Nomor Registrasi:</strong> {{ session('no_registrasi') }}
                        </div>
                    @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownToggle = document.getElementById('notificationDropdown');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', function () {
                // Panggil endpoint untuk menandai semua notifikasi sebagai sudah dibaca
                fetch("{{ route('notifications.markAllRead') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({})
                }).then(function () {
                    // Hapus badge merah secara visual tanpa reload halaman
                    const badge = dropdownToggle.querySelector('.badge');
                    if (badge) {
                        badge.remove();
                    }
                }).catch(function (error) {
                    console.error('Gagal menandai notifikasi sebagai dibaca:', error);
                });
            });
        }
    });
</script>
</body>
</html>