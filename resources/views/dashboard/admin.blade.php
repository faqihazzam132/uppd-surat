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
        @if(in_array(Auth::user()->role, ['admin', 'staff']))
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
        @endif
    </div>

    <!-- Grafis Statistik Bulanan -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistik Surat Masuk & Keluar ({{ date('Y') }})</h5>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" style="max-height: 400px;"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        // Data dari Controller
        const dataMasuk = @json($masukPerBulan);
        const dataKeluar = @json($keluarPerBulan);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: dataMasuk,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Surat Keluar',
                        data: dataKeluar,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)', // Warna warning/kuning
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>
</div>
@endsection