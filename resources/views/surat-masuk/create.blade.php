@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Surat Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">No Surat</label>
                            <input type="text" name="no_surat" value="{{ old('no_surat') }}" class="form-control @error('no_surat') is-invalid @enderror" placeholder="Contoh: 123/ABC/2025" required>
                            @error('no_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" class="form-control @error('tanggal_surat') is-invalid @enderror" required>
                            @error('tanggal_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pengirim</label>
                            <input type="text" name="pengirim" value="{{ old('pengirim') }}" class="form-control @error('pengirim') is-invalid @enderror" placeholder="Contoh: Dinas Pendidikan..." required>
                            @error('pengirim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Perihal</label>
                            <input type="text" name="perihal" value="{{ old('perihal') }}" class="form-control @error('perihal') is-invalid @enderror" placeholder="Contoh: Undangan Rapat..." required>
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sifat Surat</label>
                            <select name="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                                <option value="">-- Pilih Sifat Surat --</option>
                                <option value="Biasa" {{ old('sifat') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="Penting" {{ old('sifat') == 'Penting' ? 'selected' : '' }}>Penting</option>
                                <option value="Rahasia" {{ old('sifat') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                <option value="Segera" {{ old('sifat') == 'Segera' ? 'selected' : '' }}>Segera</option>
                            </select>
                            @error('sifat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Surat (PDF)</label>
                            <input type="file" name="file_surat" class="form-control @error('file_surat') is-invalid @enderror" accept=".pdf" required>
                            <div class="form-text">Hanya file PDF yang diperbolehkan. Maksimal ukuran file 2MB.</div>
                            @error('file_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
