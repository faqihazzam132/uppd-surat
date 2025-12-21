@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Surat Masuk</h2>
            @if(Auth::user()->role == 'staff' || Auth::user()->role == 'admin')
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Surat Masuk
            </a>
            @endif
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
                                <th>Status / Posisi</th>
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

                                            {{-- STAFF ACTIONS --}}
                                            @if(Auth::user()->role == 'staff')
                                                @if($surat->posisi == 'staff')
                                                    {{-- Tombol Teruskan --}}
                                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#forwardModal{{ $surat->id }}" title="Teruskan ke Pimpinan">
                                                        <i class="fas fa-share"></i>
                                                    </button>
                                                @endif
                                                
                                                <a href="{{ route('arsip.create_from_surat', ['type' => 'surat-masuk', 'id' => $surat->id]) }}" 
                                                   class="btn btn-sm btn-success" title="Arsipkan">
                                                   <i class="fas fa-archive"></i>
                                                </a>
                                            @endif

                                            {{-- LEADER ACTIONS --}}
                                            @if(in_array(Auth::user()->role, ['kepala_unit', 'kasubbag']) && $surat->posisi == Auth::user()->role)
                                                <a href="{{ route('disposisi.create', $surat->id) }}" class="btn btn-sm btn-primary" title="Disposisi">
                                                    <i class="fas fa-paper-plane"></i>
                                                </a>

                                                <form action="{{ route('surat-masuk.selesai', $surat->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan surat ini? Status akan berubah menjadi Selesai.')"
                                                            title="Selesai">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL FORWARD (Inside Loop for simplicity) --}}
                                <div class="modal fade" id="forwardModal{{ $surat->id }}" tabindex="-1" aria-labelledby="forwardModalLabel{{ $surat->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="forwardModalLabel{{ $surat->id }}">Teruskan Surat: {{ $surat->no_surat }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('surat-masuk.forward', $surat->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="tujuan_role" class="form-label">Teruskan Kepada:</label>
                                                        <select name="tujuan_role" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Pimpinan...</option>
                                                            <option value="kepala_unit">Kepala Unit</option>
                                                            <option value="kasubbag">Kepala Bagian (Kasubbag)</option>
                                                        </select>
                                                    </div>
                                                    <p class="small text-muted">
                                                        Surat akan dipindahkan aksesnya ke role yang dipilih.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Teruskan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

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