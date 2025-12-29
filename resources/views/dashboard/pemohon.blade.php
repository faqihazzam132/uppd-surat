@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard Pemohon</h2>
            <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Buat Pengajuan Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted fw-bold text-uppercase mb-1">Total Pengajuan</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="fs-1 text-gray-300 text-primary opacity-25">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted fw-bold text-uppercase mb-1">Menunggu Verifikasi</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $stats['menunggu'] }}</div>
                        </div>
                        <div class="fs-1 text-gray-300 text-warning opacity-25">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted fw-bold text-uppercase mb-1">Diterima</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $stats['diterima'] }}</div>
                        </div>
                        <div class="fs-1 text-gray-300 text-success opacity-25">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted fw-bold text-uppercase mb-1">Ditolak</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $stats['ditolak'] }}</div>
                        </div>
                        <div class="fs-1 text-gray-300 text-danger opacity-25">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i>5 Pengajuan Terakhir</h5>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No Registrasi</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $p)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $p->no_registrasi }}</td>
                            <td>{{ $p->jenis_surat }}</td>
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                            <td>
                                @if($p->status == 'menunggu_verifikasi')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($p->status == 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($p->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block text-gray-300"></i>
                                Belum ada riwayat pengajuan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection