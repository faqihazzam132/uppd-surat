@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Surat Masuk</h2>
            {{-- Tombol Tambah dihapus di sini, karena sudah ada menu 'Buat Surat' sendiri --}}
            {{-- Kalo mau ada redundant link bisa, tapi request minta dipisah --}}
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No Agenda</th>
                                <th>No Surat</th>
                                <th>Tanggal</th>
                                <th>Pengirim</th>
                                <th>Status</th>
                                <th style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $surat)
                                <tr>
                                    <td>{{ $surat->no_agenda }}</td>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>
                                        <div>{{ $surat->tanggal_surat }}</div>
                                        <small class="text-muted">Terima: {{ $surat->tanggal_diterima }}</small>
                                    </td>
                                    <td>{{ $surat->pengirim }}</td>
                                    <td>
                                        <span class="badge bg-{{ $surat->status == 'selesai' ? 'success' : ($surat->status == 'menunggu_disposisi' ? 'warning' : 'info') }}">
                                            {{ ucwords(str_replace('_', ' ', $surat->status)) }}
                                        </span>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ ucwords(str_replace('_', ' ', $surat->posisi)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('surat-masuk.show', $surat) }}"
                                                class="btn btn-sm btn-info text-white" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('surat-masuk.download', $surat->id) }}"
                                                class="btn btn-sm btn-secondary text-white" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>

                                            {{-- STAFF ACTIONS: ARSIP ONLY --}}
                                            @if(Auth::user()->role == 'staff')
                                                <a href="{{ route('arsip.create_from_surat', ['type' => 'surat-masuk', 'id' => $surat->id]) }}" 
                                                   class="btn btn-sm btn-success" title="Arsipkan">
                                                   <i class="fas fa-archive"></i>
                                                </a>
                                            @endif
                                            
                                            {{-- LEADERS usually just view, but if they need to 'restore' or something, it's not requested yet. --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Belum ada data surat masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection