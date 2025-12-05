@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Surat Keluar</h2>
                <div>
                    {{-- Tombol Edit: Muncul untuk Staff (Draft/Revisi) ATAU Kasubbag (Revisi) --}}
                    @if((in_array($surat->status, ['draft', 'revisi']) && Auth::user()->role == 'staff') || ($surat->status == 'revisi' && Auth::user()->role == 'kasubbag') || Auth::user()->role == 'admin')
                        <a href="{{ route('surat-keluar.edit', $surat->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
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
                        <div class="col-md-3 fw-bold">Kategori</div>
                        <div class="col-md-9">: <span class="badge bg-info text-dark">{{ $surat->kategori ?? '-' }}</span></div>
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

            {{-- Form Update Status (Kasubbag & Kepala Unit) --}}
            @if(in_array(Auth::user()->role, ['admin', 'kasubbag', 'kepala_unit']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Verifikasi & Persetujuan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('surat-keluar.status', $surat->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="status" class="form-label">Tindakan</label>
                                    <select name="status" id="status" class="form-select">
                                        {{-- Kasubbag: Draft -> Verifikasi / Revisi --}}
                                        @if(Auth::user()->role == 'kasubbag' || Auth::user()->role == 'admin')
                                            <option value="verifikasi" {{ $surat->status == 'verifikasi' ? 'selected' : '' }}>Verifikasi (Teruskan ke Kepala Unit)</option>
                                            <option value="revisi" {{ $surat->status == 'revisi' ? 'selected' : '' }}>Revisi (Kembalikan ke Staff)</option>
                                        @endif

                                        {{-- Kepala Unit: Verifikasi -> Disetujui / Revisi --}}
                                        @if(Auth::user()->role == 'kepala_unit' || Auth::user()->role == 'admin')
                                            <option value="disetujui" {{ $surat->status == 'disetujui' ? 'selected' : '' }}>Setujui (Siap Tanda Tangan)</option>
                                            <option value="revisi" {{ $surat->status == 'revisi' ? 'selected' : '' }}>Revisi (Kembalikan)</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-warning w-100">Simpan Status</button>
                                </div>
                            </div>

                            {{-- Form Tambahan untuk Revisi --}}
                            <div id="div-revisi-container" class="mt-3 p-3 bg-light border rounded" style="display: none;">
                                <h6 class="fw-bold"><i class="fas fa-undo me-1"></i> Detail Revisi</h6>
                                <div class="mb-3">
                                    <label for="tujuan_revisi" class="form-label">Kembalikan Ke</label>
                                    <select name="tujuan_revisi" id="tujuan_revisi" class="form-select">
                                        <option value="staff">Staff (Pembuat)</option>
                                        @if(Auth::user()->role == 'kepala_unit' || Auth::user()->role == 'admin')
                                            <option value="kasubbag">Kasubbag TU</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="catatan_revisi" class="form-label">Catatan Revisi</label>
                                    <textarea name="catatan_revisi" id="catatan_revisi" class="form-control" rows="3" placeholder="Jelaskan apa yang perlu diperbaiki..."></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const statusSelect = document.getElementById('status');
                        const revisiContainer = document.getElementById('div-revisi-container');
                        const tujuanRevisiSelect = document.getElementById('tujuan_revisi');
                        const catatanRevisiInput = document.getElementById('catatan_revisi');

                        function toggleRevisi() {
                            if (statusSelect.value === 'revisi') {
                                revisiContainer.style.display = 'block';
                                tujuanRevisiSelect.setAttribute('required', 'required');
                                catatanRevisiInput.setAttribute('required', 'required');
                            } else {
                                revisiContainer.style.display = 'none';
                                tujuanRevisiSelect.removeAttribute('required');
                                catatanRevisiInput.removeAttribute('required');
                            }
                        }

                        statusSelect.addEventListener('change', toggleRevisi);
                        toggleRevisi(); // Run on load
                    });
                </script>
            @endif

            {{-- Form Upload Final (Kepala Unit setelah Disetujui) --}}
            @if(in_array(Auth::user()->role, ['admin', 'kepala_unit']) && $surat->status == 'disetujui')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Tanda Tangan & Upload Final</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Silakan unduh draft, tanda tangani secara digital/basah, lalu upload kembali file finalnya di sini.</p>
                        <form action="{{ route('surat-keluar.upload-final', $surat->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file_final" class="form-label">File Final (PDF)</label>
                                <input type="file" class="form-control" id="file_final" name="file_final" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-signature me-2"></i> Upload & Kirim
                            </button>
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

            {{-- Riwayat Log Surat --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Aktivitas Surat</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surat->logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $log->user->name }} ({{ ucfirst($log->user->role) }})</td>
                                    <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                                    <td>{{ $log->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada riwayat aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
