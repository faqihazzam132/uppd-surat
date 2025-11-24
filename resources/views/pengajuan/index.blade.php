@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Pengajuan Saya</h2>
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No Registrasi</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $p)
                    <tr>
                        <td class="fw-bold">{{ $p->no_registrasi }}</td>
                        <td>{{ $p->jenis_surat }}</td>
                        <td>{{ $p->created_at->format('d M Y H:i') }}</td>
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
                        <td>
                            <!-- Tombol Aksi (Nanti bisa ditambah Detail) -->
                            <button class="btn btn-sm btn-info text-white">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada riwayat pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection