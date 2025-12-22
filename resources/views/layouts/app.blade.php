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
        body, html {
            height: 100%;
            overflow: hidden; /* Prevent body scroll */
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .sidebar {
            width: 280px;
            height: 100%;
            background: #212529; /* Darker background for premium feel */
            color: #e9ecef;
            flex-shrink: 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: #ced4da;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 25px; /* Increased padding */
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent; /* Placeholder for active border */
        }

        .sidebar a i {
            width: 24px;
            text-align: center;
            margin-right: 12px; /* Consistent spacing */
            font-size: 1.1rem;
        }

        .sidebar a:hover {
            background: #2c3034;
            color: #fff;
        }

        .sidebar a.active {
            background: #2c3034; /* Slightly lighter than main bg */
            color: #ffc107; /* Gold text for active */
            border-left-color: #ffc107;
            font-weight: 600;
        }
        
        /* Submenu adjustments */
        .sidebar .collapse a {
            padding-left: 55px; /* Deep indentation for hierarchy */
            font-size: 0.9rem;
            color: #adb5bd;
        }
        .sidebar .collapse a.active {
            color: #ffc107;
            border-left-color: transparent; /* No border for submenu items */
        }

        .sidebar small.text-muted {
            color: #6c757d !important;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 20px 25px 5px; /* Aligned with links */
            margin: 0;
        }

        .sidebar-profile {
            padding: 25px;
            background: linear-gradient(180deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 100%);
            margin-bottom: 0px;
            text-align: center;
        }
        
        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
        }

        /* Scrollbar styling for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #495057;
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-track {
            background-color: transparent;
        }

        .content {
            flex-grow: 1;
            height: 100%;
            overflow-y: auto;
            padding: 25px;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar (Hanya tampil jika login) -->
        @auth
            <div class="sidebar">
                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                    <h4 class="text-center py-3 mb-0 fw-bold" style="letter-spacing: 1px;">UPPD Kalideres</h4>
                </a>

                {{-- Profil User --}}
                <div class="sidebar-profile">
                    <div class="mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle shadow" style="width: 55px; height: 55px; font-size: 1.4rem; font-weight: bold;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="fw-bold text-white">{{ Auth::user()->name }}</div>
                    <span class="badge bg-light text-dark mt-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        {{ strtoupper(str_replace('_', ' ', Auth::user()->role)) }}
                    </span>
                </div>

                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>

                <!-- Menu Internal (Admin/Staff) -->
                @if(auth()->user()->role != 'pemohon')
                    <small class="text-muted d-block">Administrasi</small>

                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog"></i> Manajemen Pengguna
                        </a>
                    @endif

                    {{-- Menu Dropdown Surat Masuk --}}
                    <a href="#submenuSuratMasuk" data-bs-toggle="collapse" class="{{ request()->is('surat-masuk*') ? 'active' : 'collapsed' }}" aria-expanded="{{ request()->is('surat-masuk*') ? 'true' : 'false' }}">
                        <i class="fas fa-inbox"></i> Surat Masuk <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse {{ request()->is('surat-masuk*') ? 'show' : '' }}" id="submenuSuratMasuk">
                        @if(in_array(auth()->user()->role, ['admin', 'staff']))
                            <a href="{{ route('surat-masuk.create') }}" class="{{ request()->routeIs('surat-masuk.create') ? 'active' : '' }}">
                                Buat Surat Masuk
                            </a>
                        @endif
                        <a href="{{ route('surat-masuk.validasi') }}" class="{{ request()->routeIs('surat-masuk.validasi') ? 'active' : '' }}">
                            Surat Masuk Aktif
                        </a>
                        <a href="{{ route('surat-masuk.index') }}" class="{{ request()->routeIs('surat-masuk.index') ? 'active' : '' }}">
                            Data Surat Masuk
                        </a>
                    </div>

                    <a href="{{ route('disposisi.index') }}" class="{{ request()->is('disposisi*') ? 'active' : '' }}">
                        <i class="fas fa-share-square"></i> Disposisi
                    </a>

                    {{-- Logic Menu Surat Keluar --}}
                    <a href="#submenuSuratKeluar" data-bs-toggle="collapse" class="{{ request()->is('surat-keluar*') ? 'active' : 'collapsed' }}" aria-expanded="{{ request()->is('surat-keluar*') ? 'true' : 'false' }}">
                        <i class="fas fa-paper-plane"></i> Surat Keluar <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse {{ request()->is('surat-keluar*') ? 'show' : '' }}" id="submenuSuratKeluar">
                        
                        @if(Auth::user()->role == 'staff')
                            <a href="{{ route('surat-keluar.create') }}" class="{{ request()->routeIs('surat-keluar.create') ? 'active' : '' }}">
                                Buat Surat Keluar
                            </a>
                            <a href="{{ route('surat-keluar.aktif') }}" class="{{ request()->routeIs('surat-keluar.aktif') ? 'active' : '' }}">
                                Surat Keluar Aktif
                            </a>
                            <a href="{{ route('surat-keluar.index') }}" class="{{ request()->routeIs('surat-keluar.index') ? 'active' : '' }}">
                                Data Surat Keluar
                            </a>
                        @else
                            <a href="{{ route('surat-keluar.review') }}" class="{{ request()->routeIs('surat-keluar.review') ? 'active' : '' }}">
                                Review Surat Keluar
                            </a>
                            <a href="{{ route('surat-keluar.index') }}" class="{{ request()->routeIs('surat-keluar.index') ? 'active' : '' }}">
                                Data Surat Keluar
                            </a>
                        @endif
                    </div>

                    @if(in_array(auth()->user()->role, ['staff', 'admin']))
                        <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->is('admin/pengajuan*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i> {{ auth()->user()->role == 'admin' ? 'Data Pengajuan' : 'Verifikasi Pengajuan' }}
                        </a>
                    @endif

                    <a href="{{ route('arsip.index') }}" class="{{ request()->is('arsip*') ? 'active' : '' }}">
                        <i class="fas fa-archive"></i> Arsip Surat
                    </a>

                    <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Laporan
                    </a>
                @endif

                <small class="text-muted d-block">Pengaturan</small>
                <a href="{{ route('password.change') }}" class="{{ request()->routeIs('password.change') ? 'active' : '' }}">
                    <i class="fas fa-key"></i> Ubah Password
                </a>

                <div class="sidebar-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        <!-- Content -->
        <div class="content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>