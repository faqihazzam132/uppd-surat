@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Laporan Pengajuan</h2>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal dari</label>
                    <input type="date" name="tanggal_from" value="{{ $filters['tanggal_from'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal sampai</label>
                    <input type="date" name="tanggal_to" value="{{ $filters['tanggal_to'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu_verifikasi" {{ ($filters['status'] ?? '') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="diterima" {{ ($filters['status'] ?? '') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ ($filters['status'] ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Surat</label>
                    <select name="jenis_surat" class="form-select">
                        <option value="">Semua</option>
                        @foreach($jenisSuratList as $js)
                            <option value="{{ $js }}" {{ ($filters['jenis_surat'] ?? '') == $js ? 'selected' : '' }}>{{ $js }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-between mt-3">
                    <div>
                        <a href="{{ route('admin.pengajuan.export.pdf', request()->query()) }}" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary me-2">Reset</a>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No Registrasi</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $p)
                        <tr>
                            <td class="fw-bold">{{ $p->no_registrasi }}</td>
                            <td>{{ optional($p->user)->name }}</td>
                            <td>{{ $p->jenis_surat }}</td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @if($p->status == 'menunggu_verifikasi')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($p->status == 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($p->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="mb-1">
                                        @if($p->file_syarat)
                                            <a href="{{ route('admin.pengajuan.file', $p->id) }}" class="btn btn-sm btn-outline-primary mb-1" target="_blank">
                                                <i class="fas fa-file-alt me-1"></i> Lihat Lampiran
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('admin.pengajuan.verify', $p->id) }}" class="d-flex flex-column gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="menunggu_verifikasi" {{ $p->status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                            <option value="diterima" {{ $p->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        <input type="text" name="catatan_petugas" class="form-control form-control-sm" placeholder="Catatan petugas (opsional)" value="{{ $p->catatan_petugas }}">
                                        <button type="submit" class="btn btn-sm btn-success mt-1">Update</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="6">
                                <strong>Riwayat Pengajuan:</strong>
                                @if($p->logs->isEmpty())
                                    <span class="text-muted ms-2">Belum ada log.</span>
                                @else
                                    <div style="max-height: 120px; overflow-y: auto; margin-top: 4px;">
                                        <ul class="mb-0 small">
                                            @foreach($p->logs->sortByDesc('created_at') as $log)
                                                <li>
                                                    <span class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</span>
                                                    &mdash;
                                                    {{ $log->aksi }}
                                                    @if($log->user)
                                                        oleh <strong>{{ $log->user->name }}</strong>
                                                    @endif
                                                    @if($log->keterangan)
                                                        &mdash; {{ $log->keterangan }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
