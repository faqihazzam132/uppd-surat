@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Review Surat Keluar</h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-1"></i> Daftar surat yang memerlukan tindakan (Verifikasi / Persetujuan / Tanda Tangan) dari Anda.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No Surat</th>
                                <th>Dari (Staff)</th>
                                <th>Perihal</th>
                                <th>Status Saat Ini</th>
                                <th style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $surat)
                                <tr>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>{{ $surat->user->name ?? 'Staff' }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>
                                        @if($surat->status == 'draft')
                                            <span class="badge bg-secondary">Draft Baru</span>
                                        @elseif($surat->status == 'verifikasi')
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                        @elseif($surat->status == 'disetujui')
                                            <span class="badge bg-info text-dark">Siap Upload Final</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $surat->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- Lihat Dokumen --}}
                                            <a href="{{ route('surat-keluar.draft', $surat->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat Draft">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Tindak Lanjut --}}
                                            <a href="{{ route('surat-keluar.show', $surat) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-check-circle me-1"></i> Tindak Lanjut
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Tidak ada surat yang perlu direview saat ini.
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
