@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Surat Keluar</h2>
        <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Surat Keluar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Surat</th>
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
                                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                <td>{{ $surat->tujuan }}</td>
                                <td>{{ $surat->perihal }}</td>
                                <td>
                                    @if($surat->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($surat->status == 'verifikasi')
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @elseif($surat->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($surat->status == 'revisi')
                                        <span class="badge bg-warning">Perlu Revisi</span>
                                    @elseif($surat->status == 'terkirim')
                                        <span class="badge bg-primary">Terkirim</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('surat-keluar.show', $surat->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($surat->status == 'draft' || $surat->status == 'revisi')
                                        <a href="{{ route('surat-keluar.edit', $surat->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if($surat->status == 'draft' && $surat->file_draft)
                                        <a href="{{ route('surat-keluar.download-draft', $surat->id) }}" class="btn btn-sm btn-secondary" title="Unduh Draft">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                    @if($surat->file_final)
                                        <a href="{{ route('surat-keluar.download-final', $surat->id) }}" class="btn btn-sm btn-success" title="Unduh Final">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data surat keluar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $surats->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
