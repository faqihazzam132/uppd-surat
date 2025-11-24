@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="mb-3">
                <h2 class="h4 mb-1">Pengajuan Surat Baru</h2>
                <p class="text-muted small mb-0">Lengkapi data di bawah ini dengan benar agar proses verifikasi berjalan lancar.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pengajuan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Jenis Surat -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Permohonan Surat <span class="text-danger">*</span></label>
                            <select name="jenis_surat" class="form-select" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                                <option value="Surat Pengantar KTP">Surat Pengantar KTP</option>
                                <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                                <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="form-text">Pilih jenis surat yang paling sesuai dengan kebutuhan kamu.</div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan / Keperluan <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="4" placeholder="Jelaskan secara singkat dan jelas keperluan surat ini..." required></textarea>
                            <div class="form-text">Contoh: "Untuk pengajuan beasiswa semester genap tahun 2025".</div>
                        </div>

                        <!-- Upload File -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Upload Berkas Pendukung (KTP/KK) <span class="text-danger">*</span></label>
                            <input type="file" name="file_syarat" class="form-control" required accept=".pdf,.jpg,.png,.jpeg">
                            <div class="form-text text-muted">Format yang diperbolehkan: PDF, JPG, PNG. Ukuran maksimal 2MB.</div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success px-4">
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