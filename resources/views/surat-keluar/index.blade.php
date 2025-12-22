@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Surat Keluar (Selesai/Terkirim)</h2>
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
                                        <span class="badge bg-success">Terkirim / Selesai</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- Detail (Semua Role) --}}
                                            <a href="{{ route('surat-keluar.show', $surat) }}" class="btn btn-sm btn-secondary text-white" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            {{-- Download (Semua Role) --}}
                                            <a href="{{ route('surat-keluar.download', $surat->id) }}" class="btn btn-sm btn-success text-white" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>

                                            {{-- Arsip (Khusus Staff) --}}
                                            @if(Auth::user()->role == 'staff')
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
                                        Data surat keluar yang selesai belum tersedia.
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