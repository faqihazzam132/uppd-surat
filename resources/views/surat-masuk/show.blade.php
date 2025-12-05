@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Surat Masuk</h2>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Surat : {{ $suratMasuk->no_surat }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">No Agenda</div>
                        <div class="col-md-9">: {{ $suratMasuk->no_agenda }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">No Surat</div>
                        <div class="col-md-9">: {{ $suratMasuk->no_surat }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tanggal Surat</div>
                        <div class="col-md-9">: {{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tanggal Diterima</div>
                        <div class="col-md-9">: {{ \Carbon\Carbon::parse($suratMasuk->tanggal_diterima)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Pengirim</div>
                        <div class="col-md-9">: {{ $suratMasuk->pengirim }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Perihal</div>
                        <div class="col-md-9">: {{ $suratMasuk->perihal }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Sifat</div>
                        <div class="col-md-9">: <span class="badge bg-info text-dark">{{ $suratMasuk->sifat ?? '-' }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">File Surat</div>
                        <div class="col-md-9">
                            : 
                            @if($suratMasuk->file_path)
                                <a href="{{ asset('storage/'.$suratMasuk->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-alt me-1"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Arsip --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Pengarsipan</h5>
                </div>
                <div class="card-body">
                    @if($suratMasuk->arsip)
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i> Surat ini sudah diarsipkan.
                            <a href="{{ route('arsip.show', $suratMasuk->arsip->id) }}" class="fw-bold text-decoration-none ms-2">Lihat Arsip</a>
                        </div>
                    @else
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i> Surat ini belum diarsipkan.
                        </div>
                        <a href="{{ route('arsip.create_from_surat', ['type' => 'surat-masuk', 'id' => $suratMasuk->id]) }}" class="btn btn-dark">
                            <i class="fas fa-archive me-1"></i> Arsipkan Surat Ini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
