@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Surat Masuk Aktif</h2>
        </div>



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
                                        @php
                                            $badgeClass = 'info';
                                            $label = 'Baru';
                                            
                                            // Mapping status to user requested labels
                                            if ($surat->status == 'menunggu_validasi') {
                                                $label = 'Menunggu Validasi';
                                                $badgeClass = 'info';
                                            } elseif ($surat->status == 'menunggu_disposisi') {
                                                $label = 'Sudah Diteruskan / Menunggu Disposisi';
                                                $badgeClass = 'warning';
                                            } elseif ($surat->status == 'disposisi') {
                                                $label = 'Sedang Disposisi';
                                                $badgeClass = 'warning';
                                            } elseif ($surat->status == 'selesai') {
                                                $label = 'Selesai';
                                                $badgeClass = 'success';
                                            } else {
                                                // Default/Fallback
                                                $label = ucwords(str_replace('_', ' ', $surat->status));
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ ucwords(str_replace('_', ' ', $surat->posisi)) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- Tombol Lihat Dokumen --}}
                                            @if($surat->file_path)
                                                <a href="{{ route('surat-masuk.view-file', $surat->id) }}" target="_blank"
                                                    class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Dokumen">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled><i class="fas fa-eye-slash"></i></button>
                                            @endif
                                            
                                            {{-- Tombol Teruskan (Staff) --}}
                                            @if($surat->posisi == 'staff')
                                                <a href="{{ route('surat-masuk.show', $surat) }}" class="btn btn-sm btn-warning shadow-sm px-3 fw-bold" title="Teruskan Surat">
                                                    <i class="fas fa-share me-1"></i> Teruskan
                                                </a>
                                            
                                            {{-- Tombol Disposisi & Selesai (Leader) --}}
                                            @elseif(in_array(Auth::user()->role, ['kepala_unit', 'kasubbag']) && $surat->posisi == Auth::user()->role)
                                                <a href="{{ route('disposisi.create', $surat->id) }}" class="btn btn-sm btn-primary shadow-sm px-3" title="Disposisi">
                                                    <i class="fas fa-paper-plane me-1"></i> Disposisi
                                                </a>
                                                
                                                <form action="{{ route('surat-masuk.selesai', $surat->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success shadow-sm px-3" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan surat ini?')"
                                                            title="Selesai">
                                                        <i class="fas fa-check me-1"></i> Selesai
                                                    </button>
                                                </form>

                                            {{-- Fallback / Detail --}}
                                            @else
                                                <a href="{{ route('surat-masuk.show', $surat) }}" class="btn btn-sm btn-light border shadow-sm px-3" title="Lihat Status & Detail">
                                                    <i class="fas fa-info-circle me-1 text-secondary"></i> <span class="text-secondary">Detail</span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Tidak ada surat yang perlu divalidasi saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection