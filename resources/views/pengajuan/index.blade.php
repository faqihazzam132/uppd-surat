@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h2 class="h4 mb-1">Riwayat Pengajuan Saya</h2>
            <p class="text-muted mb-0">Pantau status pengajuan surat yang sudah pernah kamu ajukan.</p>
        </div>
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Buat Pengajuan Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th class="px-4">No Registrasi</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $p)
                        <tr>
                            <td class="fw-semibold px-4">
                                <span class="badge bg-light text-primary border">{{ $p->no_registrasi }}</span>
                            </td>
                            <td>{{ $p->jenis_surat }}</td>
                            <td class="text-muted small">
                                {{ $p->created_at->format('d M Y') }}<br>
                                <span class="text-secondary">{{ $p->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td>
                                @if($p->status == 'menunggu_verifikasi')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($p->status == 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($p->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <!-- Tombol Aksi (Nanti bisa ditambah Detail) -->
                                <button class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-3">
                                    <div class="mb-2">
                                        <i class="fas fa-folder-open fa-2x text-muted"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Belum ada riwayat pengajuan</h6>
                                    <p class="text-muted small mb-3">Silakan buat pengajuan surat pertama kamu dengan menekan tombol di atas.</p>
                                    <a href="{{ route('pengajuan.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Buat Pengajuan
                                    </a>
                                </div>
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