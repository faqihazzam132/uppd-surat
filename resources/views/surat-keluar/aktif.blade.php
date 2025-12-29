@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Surat Keluar Aktif</h2>
            <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Buat Surat Keluar
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i> Daftar surat yang sedang dalam proses pengajuan, revisi, atau verifikasi.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No Surat</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Perihal</th>
                                <th>Status</th>
                                <th style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $surat)
                                <tr>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>{{ $surat->tanggal_surat }}</td>
                                    <td>{{ $surat->tujuan }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>
                                        @if($surat->status == 'draft')
                                            <span class="badge bg-secondary">Draft (Menunggu Verifikasi)</span>
                                        @elseif($surat->status == 'verifikasi')
                                            <span class="badge bg-warning text-dark">Sedang Diverifikasi</span>
                                        @elseif($surat->status == 'disetujui')
                                            <span class="badge bg-info text-dark">Disetujui (Menunggu TTD)</span>
                                        @elseif($surat->status == 'revisi')
                                            <span class="badge bg-danger">Perlu Revisi</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $surat->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- Lihat Dokumen --}}
                                            <a href="{{ route('surat-keluar.draft', $surat->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat Draft">
                                                <i class="fas fa-file-alt"></i>
                                            </a>

                                            {{-- Detail / Edit (jika revisi) --}}
                                            <a href="{{ route('surat-keluar.show', $surat) }}" class="btn btn-sm btn-primary" title="Detail / Proses">
                                                Detail & Proses
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Tidak ada surat keluar yang sedang aktif diproses.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
