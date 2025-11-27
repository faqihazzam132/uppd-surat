@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Buat Surat Keluar Baru</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <label for="tujuan" class="col-md-4 col-form-label text-md-end">Tujuan Surat</label>
                            <div class="col-md-6">
                                <input id="tujuan" type="text" class="form-control @error('tujuan') is-invalid @enderror" name="tujuan" value="{{ old('tujuan') }}" required autofocus>
                                @error('tujuan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="perihal" class="col-md-4 col-form-label text-md-end">Perihal</label>
                            <div class="col-md-6">
                                <textarea id="perihal" class="form-control @error('perihal') is-invalid @enderror" name="perihal" required>{{ old('perihal') }}</textarea>
                                @error('perihal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tanggal_surat" class="col-md-4 col-form-label text-md-end">Tanggal Surat</label>
                            <div class="col-md-6">
                                <input id="tanggal_surat" type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" name="tanggal_surat" value="{{ old('tanggal_surat', now()->format('Y-m-d')) }}" required>
                                @error('tanggal_surat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="file_draft" class="col-md-4 col-form-label text-md-end">File Draft</label>
                            <div class="col-md-6">
                                <input id="file_draft" type="file" class="form-control @error('file_draft') is-invalid @enderror" name="file_draft" accept=".pdf,.doc,.docx" required>
                                <small class="form-text text-muted">Format file: PDF, DOC, DOCX (Maks: 2MB)</small>
                                @error('file_draft')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="keterangan" class="col-md-4 col-form-label text-md-end">Keterangan (Opsional)</label>
                            <div class="col-md-6">
                                <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Draft
                                </button>
                                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
