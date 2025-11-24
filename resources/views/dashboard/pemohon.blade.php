@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Halo, {{ Auth::user()->name }}!</h2>
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajukan Permohonan Baru
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Riwayat Permohonan Saya</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No Registrasi</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $p)
                    <tr>
                        <td class="fw-bold text-primary">{{ $p->no_registrasi }}</td>
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
                        <td class="text-muted small">{{ $p->catatan_petugas ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Belum ada permohonan yang diajukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection