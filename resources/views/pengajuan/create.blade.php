@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pengajuan Surat Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Jenis Surat -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Permohonan Surat</label>
                            <select name="jenis_surat" class="form-select" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                                <option value="Surat Pengantar KTP">Surat Pengantar KTP</option>
                                <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                                <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Keterangan / Keperluan</label>
                            <textarea name="keterangan" class="form-control" rows="4" placeholder="Jelaskan keperluan surat ini..." required></textarea>
                        </div>

                        <!-- Upload File -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload Berkas Pendukung (KTP/KK)</label>
                            <input type="file" name="file_syarat" class="form-control" required accept=".pdf,.jpg,.png,.jpeg">
                            <div class="form-text text-muted">Format: PDF, JPG, PNG. Maksimal 2MB.</div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection