@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4"><i class="fas fa-share-square me-2"></i>Manajemen Disposisi</h2>

        <!-- Section 1: Surat Masuk yang Menunggu Disposisi (Khusus Kepala Unit) -->
        @if(auth()->user()->role == 'kepala_unit')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Menunggu Disposisi</h5>
                </div>
                <div class="card-body">
                    @if($suratBelumDisposisi->isEmpty())
                        <p class="text-muted mb-0">Tidak ada surat yang menunggu disposisi.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Surat</th>
                                        <th>Perihal</th>
                                        <th>Pengirim</th>
                                        <th>Tanggal Diterima</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suratBelumDisposisi as $surat)
                                        <tr>
                                            <td>{{ $surat->no_surat }}</td>
                                            <td>{{ Str::limit($surat->perihal, 50) }}</td>
                                            <td>{{ $surat->pengirim }}</td>
                                            <td>{{ $surat->tanggal_diterima }}</td>
                                            <td>
                                                <a href="{{ route('disposisi.create', $surat->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-paper-plane me-1"></i> Disposisi Sekarang
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Section 2: Disposisi Masuk (Kotak Masuk Tugas) - Untuk Kasubbag & Staff -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-inbox me-2"></i>Disposisi Masuk (Tugas Saya)</h5>
            </div>
            <div class="card-body">
                @if($disposisiMasuk->isEmpty())
                    <p class="text-muted mb-0">Tidak ada disposisi masuk saat ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Dari</th>
                                    <th>Perihal Surat</th>
                                    <th>Instruksi</th>
                                    <th>Batas Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disposisiMasuk as $disp)
                                    <tr>
                                        <td>{{ $disp->pengirim->name ?? 'Pengguna Terhapus' }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $disp->suratMasuk->no_surat }}</div>
                                            <small class="text-muted">{{ Str::limit($disp->suratMasuk->perihal, 30) }}</small>
                                        </td>
                                        <td>{{ Str::limit($disp->instruksi, 40) }}</td>
                                        <td>
                                            @if($disp->batas_waktu)
                                                <span class="badge {{ $disp->batas_waktu < date('Y-m-d') ? 'bg-danger' : 'bg-info' }}">
                                                    {{ $disp->batas_waktu }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($disp->status == 'belum_dibaca')
                                                <span class="badge bg-danger">Baru</span>
                                            @elseif($disp->status == 'diproses')
                                                <span class="badge bg-warning text-dark">Diproses</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('disposisi.show', $disp->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Buka
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Riwayat Disposisi Keluar (Yang Saya Kirimkan) -->
        @if(auth()->user()->role == 'kepala_unit' || auth()->user()->role == 'kasubbag')
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Disposisi Keluar</h5>
                </div>
                <div class="card-body">
                    @if($disposisiTerkirim->isEmpty())
                        <p class="text-muted mb-0">Belum ada riwayat disposisi keluar.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kepada</th>
                                        <th>No. Surat</th>
                                        <th>Instruksi</th>
                                        <th>Status Penerima</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($disposisiTerkirim as $disp)
                                        <tr>
                                            <td>{{ $disp->penerima->name ?? 'Pengguna Terhapus' }}</td>
                                            <td>{{ $disp->suratMasuk->no_surat }}</td>
                                            <td>{{ Str::limit($disp->instruksi, 40) }}</td>
                                            <td>
                                                @if($disp->status == 'belum_dibaca')
                                                    <span class="badge bg-secondary">Belum Dibaca</span>
                                                @elseif($disp->status == 'diproses')
                                                    <span class="badge bg-warning text-dark">Sedang Proses</span>
                                                @else
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            </td>
                                            <td>{{ $disp->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection