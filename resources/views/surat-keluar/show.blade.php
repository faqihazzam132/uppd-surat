@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Surat Keluar</h2>
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Surat : {{ $surat->no_surat }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">No Surat</div>
                        <div class="col-md-9">: {{ $surat->no_surat }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tanggal Surat</div>
                        <div class="col-md-9">: {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tujuan</div>
                        <div class="col-md-9">: {{ $surat->tujuan }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Perihal</div>
                        <div class="col-md-9">: {{ $surat->perihal }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Status</div>
                        <div class="col-md-9">
                            : 
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
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">File Draft</div>
                        <div class="col-md-9">
                            : 
                            @if($surat->file_draft)
                                <a href="{{ asset('storage/' . $surat->file_draft) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-alt me-1"></i> Lihat Draft
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">File Final</div>
                        <div class="col-md-9">
                            : 
                            @if($surat->file_final)
                                <a href="{{ asset('storage/' . $surat->file_final) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-file-check me-1"></i> Lihat File Final
                                </a>
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Dibuat Oleh</div>
                        <div class="col-md-9">: {{ $surat->user->name ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Form Update Status (Hanya untuk Admin, Kasubbag, Kepala Unit) --}}
            @if(in_array(Auth::user()->role, ['admin', 'kasubbag', 'kepala_unit']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Update Status Surat</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('surat-keluar.status', $surat->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="status" class="form-label">Pilih Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="verifikasi" {{ $surat->status == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                        <option value="revisi" {{ $surat->status == 'revisi' ? 'selected' : '' }}>Revisi</option>
                                        <option value="disetujui" {{ $surat->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="terkirim" {{ $surat->status == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-warning w-100">Update Status</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Form Upload Final (Hanya untuk Admin, Staff jika status disetujui) --}}
            @if(in_array(Auth::user()->role, ['admin', 'staff']) && in_array($surat->status, ['disetujui', 'terkirim']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Upload File Final (Tanda Tangan)</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('surat-keluar.upload-final', $surat->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file_final" class="form-label">File Final (PDF)</label>
                                <input type="file" class="form-control" id="file_final" name="file_final" required>
                            </div>
                            <button type="submit" class="btn btn-success">Upload File Final</button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Info Arsip --}}
            @if($surat->status == 'terkirim')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Pengarsipan</h5>
                    </div>
                    <div class="card-body">
                        @if($surat->arsip)
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i> Surat ini sudah diarsipkan.
                                <a href="{{ route('arsip.show', $surat->arsip->id) }}" class="fw-bold text-decoration-none ms-2">Lihat Arsip</a>
                            </div>
                        @else
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i> Surat ini belum diarsipkan.
                            </div>
                            <a href="{{ route('arsip.create_from_surat', ['type' => 'surat-keluar', 'id' => $surat->id]) }}" class="btn btn-dark">
                                <i class="fas fa-archive me-1"></i> Arsipkan Surat Ini
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
