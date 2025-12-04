@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Surat Keluar</h2>
        <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Surat Keluar
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $surat)
                        <tr>
                            <td>{{ $surat->no_surat }}</td>
                            <td>{{ $surat->tanggal_surat }}</td>
                            <td>{{ $surat->tujuan }}</td>
                            <td>{{ $surat->perihal }}</td>
                            <td>
                                @if($surat->status == 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($surat->status == 'verifikasi')
                                    <span class="badge bg-warning text-dark">Verifikasi</span>
                                @elseif($surat->status == 'disetujui')
                                    <span class="badge bg-info text-dark">Disetujui</span>
                                @elseif($surat->status == 'revisi')
                                    <span class="badge bg-danger">Revisi</span>
                                @elseif($surat->status == 'terkirim')
                                    <span class="badge bg-success">Terkirim</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ $surat->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('surat-keluar.show', $surat) }}" class="btn btn-sm btn-info text-white">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada data surat keluar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
