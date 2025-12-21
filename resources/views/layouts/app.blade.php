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
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #495057;
            color: white;
        }

        .sidebar small.text-muted {
            color: #ffc107 !important;
        }

        .content {
            padding: 20px;
        }
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
                    <small class="text-muted mt-3 d-block ps-3">Administrasi</small>

                    <a href="{{ route('surat-masuk.index') }}" class="{{ request()->is('surat-masuk*') ? 'active' : '' }}">
                        <i class="fas fa-inbox me-2"></i> Surat Masuk
                    </a>

                    <a href="{{ route('disposisi.index') }}" class="{{ request()->is('disposisi*') ? 'active' : '' }}">
                        <i class="fas fa-share-square me-2"></i> Disposisi
                    </a>

                    {{-- Logic Menu Surat Keluar --}}
                    @if(auth()->user()->role == 'staff')
                        {{-- Menu Dropdown untuk Staff --}}
                        <a href="#submenuSuratKeluar" data-bs-toggle="collapse" class="{{ request()->is('surat-keluar*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane me-2"></i> Surat Keluar <i class="fas fa-chevron-down float-end mt-1" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="collapse {{ request()->is('surat-keluar*') ? 'show' : '' }}" id="submenuSuratKeluar" style="background: #2c3034;">
                            <a href="{{ route('surat-keluar.create') }}" class="ps-4 {{ request()->routeIs('surat-keluar.create') ? 'text-white fw-bold' : '' }}">
                                <i class="fas fa-plus me-2"></i> Buat Surat Keluar
                            </a>
                            <a href="{{ route('surat-keluar.index') }}" class="ps-4 {{ request()->routeIs('surat-keluar.index') ? 'text-white fw-bold' : '' }}">
                                <i class="fas fa-list me-2"></i> Data Surat Keluar
                            </a>
                        </div>
                    @else
                        {{-- Menu Single Link untuk Role Lain (Kasubbag/Kepala Unit) --}}
                        <a href="{{ route('surat-keluar.index') }}" class="{{ request()->is('surat-keluar*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane me-2"></i> Surat Keluar
                        </a>
                    @endif

                    @if(auth()->user()->role != 'kepala_unit')
                        <a href="{{ route('admin.pengajuan.index') }}"
                            class="{{ request()->is('admin/pengajuan*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check me-2"></i> Verifikasi Pengajuan
                        </a>
                    @endif

                    <a href="{{ route('arsip.index') }}" class="{{ request()->is('arsip*') ? 'active' : '' }}">
                        <i class="fas fa-archive me-2"></i> Arsip Surat
                    </a>

                    <a href="#" class="">
                        <i class="fas fa-chart-bar me-2"></i> Laporan
                    </a>
                @endif

                <small class="text-muted mt-3 d-block ps-3">Pengaturan</small>
                <a href="#">
                    <i class="fas fa-key me-2"></i> Ubah Password
                </a>

                <div class="mt-4 px-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        <!-- Content -->
        <div class="content flex-grow-1">
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