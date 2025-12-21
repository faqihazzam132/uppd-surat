@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                @if(Auth::user()->role == 'staff')
                    Data Surat Keluar
                @else
                    Validasi Surat Keluar
                @endif
            </h2>
            @if(Auth::user()->role == 'staff')
                <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Buat Surat Keluar
                </a>
            @endif
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No Surat</th>
                                <th>Tanggal Surat</th>
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
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- Lihat Dokumen (Semua Role) --}} 
                                            {{-- Prioritas File Final, jika tidak ada File Draft --}}
                                            <a href="{{ route('surat-keluar.draft', $surat->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat Dokumen">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Detail (Semua Role) --}}
                                            <a href="{{ route('surat-keluar.show', $surat) }}" class="btn btn-sm btn-secondary text-white" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            {{-- Download (Khusus Kepala Unit sesuai request) --}}
                                            @if(Auth::user()->role == 'kepala_unit')
                                                <a href="{{ route('surat-keluar.download', $surat->id) }}" class="btn btn-sm btn-success text-white" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif

                                            {{-- Arsip (Khusus Staff & Status Terkirim) --}}
                                            @if(Auth::user()->role == 'staff' && $surat->status == 'terkirim')
                                                <a href="{{ route('arsip.create_from_surat', ['type' => 'surat-keluar', 'id' => $surat->id]) }}" 
                                                   class="btn btn-sm btn-dark" title="Arsipkan">
                                                    <i class="fas fa-archive"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        @if(Auth::user()->role == 'staff')
                                            Belum ada data surat keluar.
                                        @else
                                            Tidak ada surat yang perlu ditindaklanjuti.
                                        @endif
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