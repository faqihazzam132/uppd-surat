@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Surat Masuk</h2>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">No Surat</label>
                    <input type="text" name="no_surat" value="{{ old('no_surat') }}" class="form-control @error('no_surat') is-invalid @enderror" required>
                    @error('no_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" class="form-control @error('tanggal_surat') is-invalid @enderror">
                    @error('tanggal_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Pengirim</label>
                    <input type="text" name="pengirim" value="{{ old('pengirim') }}" class="form-control @error('pengirim') is-invalid @enderror" required>
                    @error('pengirim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Perihal</label>
                    <input type="text" name="perihal" value="{{ old('perihal') }}" class="form-control @error('perihal') is-invalid @enderror" required>
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Sifat</label>
                    <input type="text" name="sifat" value="{{ old('sifat') }}" class="form-control @error('sifat') is-invalid @enderror">
                    @error('sifat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">File Surat (PDF/JPG/PNG)</label>
                    <input type="file" name="file_surat" class="form-control @error('file_surat') is-invalid @enderror" required>
                    @error('file_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
