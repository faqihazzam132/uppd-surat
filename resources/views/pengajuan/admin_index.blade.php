@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Verifikasi Pengajuan</h2>
            <p class="text-muted mb-0">Kelola dan verifikasi pengajuan surat dari pemohon.</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-filter me-2"></i>Filter Pencarian</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pengajuan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tanggal Dari</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="tanggal_from" value="{{ $filters['tanggal_from'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tanggal Sampai</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="tanggal_to" value="{{ $filters['tanggal_to'] ?? '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu_verifikasi" {{ ($filters['status'] ?? '') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="diterima" {{ ($filters['status'] ?? '') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ ($filters['status'] ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Jenis Surat</label>
                        <select name="jenis_surat" class="form-select">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSuratList as $js)
                                <option value="{{ $js }}" {{ ($filters['jenis_surat'] ?? '') == $js ? 'selected' : '' }}>{{ $js }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.pengajuan.export.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF Laporan
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-light border btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">No Registrasi</th>
                            <th class="py-3">Pemohon</th>
                            <th class="py-3">Jenis Surat</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $p)
                            <tr>
                                <td class="px-4 fw-bold text-primary">{{ $p->no_registrasi }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                        <span>{{ optional($p->user)->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $p->jenis_surat }}</td>
                                <td>{{ $p->created_at->format('d M Y') }}<br><small class="text-muted">{{ $p->created_at->format('H:i') }}</small></td>
                                <td class="text-center">
                                    @if($p->status == 'menunggu_verifikasi')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                    @elseif($p->status == 'diterima')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Diterima</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 text-center">
                                    <button type="button" class="btn btn-sm btn-info text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalVerify{{ $p->id }}">
                                        <i class="fas fa-edit me-1"></i> Detail & Verifikasi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" alt="Empty" style="width: 64px; opacity: 0.5;" class="mb-3">
                                    <p class="mb-0">Tidak ada data pengajuan yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODALS FOR EACH ITEM --}}
@foreach($pengajuans as $p)
<div class="modal fade" id="modalVerify{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fas fa-clipboard-check me-2"></i> Verifikasi Pengajuan: {{ $p->no_registrasi }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    {{-- Left Column: Details --}}
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-secondary mb-3">Informasi Pengajuan</h6>
                        <table class="table table-sm table-borderless fs-6">
                            <tr>
                                <td class="text-muted" width="120">Pemohon</td>
                                <td class="fw-bold">{{ optional($p->user)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Surat</td>
                                <td>{{ $p->jenis_surat }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal</td>
                                <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">File Syarat</td>
                                <td>
                                    @if($p->file_syarat)
                                        <a href="{{ route('admin.pengajuan.file', $p->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-file-download me-1"></i> Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <h6 class="fw-bold text-secondary mt-4 mb-3">Form Verifikasi</h6>
                        <form method="POST" action="{{ route('admin.pengajuan.verify', $p->id) }}" class="p-3 bg-light rounded border">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Update Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="menunggu_verifikasi" {{ $p->status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="diterima" {{ $p->status == 'diterima' ? 'selected' : '' }}>Diterima (Setujui)</option>
                                    <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan Petugas</label>
                                <textarea name="catatan_petugas" class="form-control" rows="3" placeholder="Berikan alasan jika ditolak, atau catatan tambahan...">{{ $p->catatan_petugas }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-save me-1"></i> Simpan & Update Status
                            </button>
                        </form>
                    </div>

                    {{-- Right Column: History --}}
                    <div class="col-md-6">
                        <h6 class="fw-bold text-secondary mb-3 ms-2">Riwayat Proses</h6>
                        <div class="timeline p-2" style="max-height: 400px; overflow-y: auto;">
                            @forelse($p->logs->sortByDesc('created_at') as $log)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                            <i class="fas fa-history text-muted small"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small fw-bold text-dark">{{ $log->aksi }}</div>
                                        <div class="small text-muted mb-1">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                        @if($log->user)
                                            <div class="badge bg-light text-secondary border">Oleh: {{ $log->user->name }}</div>
                                        @endif
                                        @if($log->keterangan)
                                            <div class="small mt-1 text-dark fst-italic">"{{ $log->keterangan }}"</div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <small>Belum ada riwayat.</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
