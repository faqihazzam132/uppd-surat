@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Surat Masuk</h5>
                    <div class="btn-group">
                        <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('surat-masuk.download', $surat->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                        @can('update', $surat)
                        <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endcan
                        @can('delete', $surat)
                        <form action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Nomor Agenda</div>
                        <div class="col-md-8">{{ $surat->no_agenda }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Nomor Surat</div>
                        <div class="col-md-8">{{ $surat->no_surat }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Tanggal Surat</div>
                        <div class="col-md-8">{{ $surat->tanggal_surat->format('d F Y') }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Tanggal Diterima</div>
                        <div class="col-md-8">{{ $surat->tanggal_diterima->format('d F Y H:i') }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Pengirim</div>
                        <div class="col-md-8">{{ $surat->pengirim }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Perihal</div>
                        <div class="col-md-8">{{ $surat->perihal }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Sifat Surat</div>
                        <div class="col-md-8">
                            @if($surat->sifat == 'biasa')
                                <span class="badge bg-secondary">Biasa</span>
                            @elseif($surat->sifat == 'penting')
                                <span class="badge bg-warning">Penting</span>
                            @elseif($surat->sifat == 'rahasia')
                                <span class="badge bg-danger">Rahasia</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Status</div>
                        <div class="col-md-8">
                            @if($surat->status == 'menunggu_disposisi')
                                <span class="badge bg-warning">Menunggu Disposisi</span>
                            @elseif($surat->status == 'diproses')
                                <span class="badge bg-info">Diproses</span>
                            @elseif($surat->status == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Dokumen</div>
                        <div class="col-md-8">
                            <a href="{{ route('surat-masuk.download', $surat->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf"></i> Unduh Dokumen
                            </a>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Dibuat Oleh</div>
                        <div class="col-md-8">{{ $surat->user->name }}</div>
                    </div>
                </div>

                <div class="card-footer text-muted">
                    <small>Terakhir diperbarui: {{ $surat->updated_at->diffForHumans() }}</small>
                </div>
            </div>

            <!-- Disposisi Section -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Disposisi</h5>
                    @if($surat->status == 'menunggu_disposisi')
                    <a href="{{ route('surat-masuk.disposisi.create', $surat->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Buat Disposisi
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($surat->disposisi)
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th width="30%">Tujuan Disposisi</th>
                                    <td>{{ $surat->disposisi->tujuan }}</td>
                                </tr>
                                <tr>
                                    <th>Isi Disposisi</th>
                                    <td>{{ $surat->disposisi->isi }}</td>
                                </tr>
                                <tr>
                                    <th>Batas Waktu</th>
                                    <td>{{ $surat->disposisi->batas_waktu->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($surat->disposisi->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif($surat->disposisi->status == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada disposisi untuk surat ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
