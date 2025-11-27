@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Surat Masuk</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <label for="no_surat" class="col-md-4 col-form-label text-md-end">Nomor Surat</label>
                            <div class="col-md-6">
                                <input id="no_surat" type="text" class="form-control @error('no_surat') is-invalid @enderror" name="no_surat" value="{{ old('no_surat') }}" required autofocus>
                                @error('no_surat')
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
                            <label for="pengirim" class="col-md-4 col-form-label text-md-end">Pengirim</label>
                            <div class="col-md-6">
                                <input id="pengirim" type="text" class="form-control @error('pengirim') is-invalid @enderror" name="pengirim" value="{{ old('pengirim') }}" required>
                                @error('pengirim')
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
                            <label for="sifat" class="col-md-4 col-form-label text-md-end">Sifat Surat</label>
                            <div class="col-md-6">
                                <select id="sifat" class="form-select @error('sifat') is-invalid @enderror" name="sifat" required>
                                    <option value="biasa" {{ old('sifat') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                                    <option value="penting" {{ old('sifat') == 'penting' ? 'selected' : '' }}>Penting</option>
                                    <option value="rahasia" {{ old('sifat') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                                </select>
                                @error('sifat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="file_surat" class="col-md-4 col-form-label text-md-end">File Surat</label>
                            <div class="col-md-6">
                                <input id="file_surat" type="file" class="form-control @error('file_surat') is-invalid @enderror" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="form-text text-muted">Format file: PDF, JPG, PNG (Maks: 2MB)</small>
                                @error('file_surat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
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
