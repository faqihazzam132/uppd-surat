@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Surat Keluar</h5>
                    <div class="btn-group">
                        <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        @if($surat->file_draft)
                        <a href="{{ route('surat-keluar.download-draft', $surat->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download"></i> Draft
                        </a>
                        @endif
                        @if($surat->file_final)
                        <a href="{{ route('surat-keluar.download-final', $surat->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-pdf"></i> Final
                        </a>
                        @endif
                        @can('update', $surat)
                        <a href="{{ route('surat-keluar.edit', $surat->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        @can('delete', $surat)
                        <form action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Nomor Surat</div>
                        <div class="col-md-8">{{ $surat->no_surat }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Tanggal Surat</div>
                        <div class="col-md-8">{{ $surat->tanggal_surat->format('d F Y') }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Tujuan</div>
                        <div class="col-md-8">{{ $surat->tujuan }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Perihal</div>
                        <div class="col-md-8">{{ $surat->perihal }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Status</div>
                        <div class="col-md-8">
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
                        </div>
                    </div>

                    @if($surat->keterangan)
                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Keterangan</div>
                        <div class="col-md-8">{{ $surat->keterangan }}</div>
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Dokumen</div>
                        <div class="col-md-8">
                            @if($surat->file_draft)
                                <a href="{{ route('surat-keluar.download-draft', $surat->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                    <i class="fas fa-file-word"></i> Unduh Draft
                                </a>
                            @endif
                            @if($surat->file_final)
                                <a href="{{ route('surat-keluar.download-final', $surat->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> Unduh Final
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Dibuat Oleh</div>
                        <div class="col-md-8">{{ $surat->user->name }}</div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Terakhir diperbarui: {{ $surat->updated_at->diffForHumans() }}</small>
                        
                        @if($surat->status == 'draft' || $surat->status == 'revisi')
                            <a href="{{ route('surat-keluar.verifikasi', $surat->id) }}" 
                               class="btn btn-primary btn-sm"
                               onclick="return confirm('Kirim surat untuk diverifikasi?')">
                                <i class="fas fa-paper-plane"></i> Ajukan Verifikasi
                            </a>
                        @endif
                        
                        @can('verifikasi', $surat)
                            @if($surat->status == 'verifikasi')
                            <div class="btn-group">
                                <a href="#" class="btn btn-success btn-sm"
                                   onclick="event.preventDefault(); document.getElementById('approve-form').submit();">
                                    <i class="fas fa-check"></i> Setujui
                                </a>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Revisi
                                </button>
                            </div>
                            
                            <form id="approve-form" action="{{ route('surat-keluar.approve', $surat->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('PUT')
                            </form>
                            @endif
                        @endcan
                        
                        @can('upload-final', $surat)
                            @if($surat->status == 'disetujui' && !$surat->file_final)
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadFinalModal">
                                <i class="fas fa-upload"></i> Upload Final
                            </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@can('verifikasi', $surat)
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('surat-keluar.reject', $surat->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Permintaan Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label">Alasan Revisi</label>
                        <textarea class="form-control" id="reject_reason" name="reject_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Permintaan Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Upload Final Modal -->
@can('upload-final', $surat)
<div class="modal fade" id="uploadFinalModal" tabindex="-1" aria-labelledby="uploadFinalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('surat-keluar.upload-final', $surat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFinalModalLabel">Upload Surat Final</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_final" class="form-label">File Surat (PDF)</label>
                        <input class="form-control" type="file" id="file_final" name="file_final" accept=".pdf" required>
                        <small class="form-text text-muted">Unggah file surat yang sudah ditandatangani (format PDF)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection
