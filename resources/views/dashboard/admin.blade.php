@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Admin</h2>

    <div class="row">
        <!-- Card Surat Masuk -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Surat Masuk</h6>
                            <h2 class="mb-0">{{ $totalMasuk }}</h2>
                        </div>
                        <i class="fas fa-inbox fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Surat Keluar -->
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Surat Keluar</h6>
                            <h2 class="mb-0">{{ $totalKeluar }}</h2>
                        </div>
                        <i class="fas fa-paper-plane fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Disposisi -->
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Perlu Disposisi</h6>
                            <h2 class="mb-0">{{ $totalDisposisi }}</h2>
                        </div>
                        <i class="fas fa-exclamation-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Pengajuan -->
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Verifikasi Pengajuan</h6>
                            <h2 class="mb-0">{{ $pengajuanBaru }}</h2>
                        </div>
                        <i class="fas fa-user-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Grafik (Nanti bisa pakai Chart.js) -->
    <div class="card mt-4">
        <div class="card-header">Statistik Bulanan</div>
        <div class="card-body">
            <p class="text-muted text-center py-5">Grafik Statistik akan tampil di sini.</p>
        </div>
    </div>
</div>
@endsection