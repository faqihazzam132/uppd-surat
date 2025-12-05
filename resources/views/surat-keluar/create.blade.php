@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Buat Surat Keluar Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                            <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                            @error('tanggal_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tujuan" class="form-label">Tujuan Surat</label>
                            <input type="text" class="form-control @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" value="{{ old('tujuan') }}" placeholder="Contoh: Kepala Dinas..." required>
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="perihal" class="form-label">Perihal</label>
                            <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal') }}" placeholder="Contoh: Undangan Rapat..." required>
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Surat</label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Biasa" {{ old('kategori') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="Penting" {{ old('kategori') == 'Penting' ? 'selected' : '' }}>Penting</option>
                                <option value="Rahasia" {{ old('kategori') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                <option value="Segera" {{ old('kategori') == 'Segera' ? 'selected' : '' }}>Segera</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file_draft" class="form-label">Upload Draft Surat (PDF)</label>
                            <input type="file" class="form-control @error('file_draft') is-invalid @enderror" id="file_draft" name="file_draft" accept=".pdf" required>
                            <div class="form-text">Hanya file PDF yang diperbolehkan. Maksimal ukuran file 2MB.</div>
                            @error('file_draft')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Draft</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
