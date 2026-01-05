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
                                <option value="">-- Pilih Jenis Permohonan --</option>
                                <option value="Permohonan Salinan SPPT PBB">Permohonan Salinan SPPT PBB</option>
                                <option value="Permohonan Mutasi / Balik Nama PBB">Permohonan Mutasi / Balik Nama PBB</option>
                                <option value="Permohonan Pengurangan PBB-P2">Permohonan Pengurangan PBB-P2</option>
                                <option value="Permohonan Surat Keterangan NJOP">Permohonan Surat Keterangan NJOP</option>
                                <option value="Permohonan Pembetulan SKPD">Permohonan Pembetulan SKPD</option>
                                <option value="Permohonan Angsuran Pajak Daerah">Permohonan Angsuran Pajak Daerah</option>
                                <option value="Permohonan Keberatan Pajak">Permohonan Keberatan Pajak</option>
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
                            
                            <div id="file-inputs-container">
                                <div class="input-group mb-2">
                                    <input type="file" name="file_syarat[]" class="form-control" required accept=".pdf,.jpg,.png,.jpeg">
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="add-file-btn">
                                <i class="fas fa-plus"></i> Tambah Dokumen Lain
                            </button>

                            <div class="form-text text-muted">Format: PDF, JPG, PNG. Maksimal 2MB per file.</div>
                        </div>

                        <script>
                            document.getElementById('add-file-btn').addEventListener('click', function() {
                                var container = document.getElementById('file-inputs-container');
                                var div = document.createElement('div');
                                div.className = 'input-group mb-2';
                                div.innerHTML = `
                                    <input type="file" name="file_syarat[]" class="form-control" required accept=".pdf,.jpg,.png,.jpeg">
                                    <button type="button" class="btn btn-outline-danger remove-file-btn" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                `;
                                container.appendChild(div);
                            });

                            document.addEventListener('click', function(e) {
                                if (e.target.closest('.remove-file-btn')) {
                                    e.target.closest('.remove-file-btn').parentElement.remove();
                                }
                            });
                        </script>

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