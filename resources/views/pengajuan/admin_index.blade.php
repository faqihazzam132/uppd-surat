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
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No Registrasi</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
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
