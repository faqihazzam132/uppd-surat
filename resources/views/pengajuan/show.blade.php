@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Pengajuan</h2>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <strong>No Registrasi:</strong><br>
                {{ $pengajuan->no_registrasi }}
            </div>
            <div class="mb-3">
                <strong>Jenis Surat:</strong><br>
                {{ $pengajuan->jenis_surat }}
            </div>
            <div class="mb-3">
                <strong>Tanggal Pengajuan:</strong><br>
                {{ $pengajuan->created_at->format('d M Y H:i') }}
            </div>
            <div class="mb-3">
                <strong>Status:</strong><br>
                {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
            </div>
            <div class="mb-3">
                <strong>Keterangan:</strong><br>
                {{ $pengajuan->keterangan }}
            </div>
            <div class="mb-3">
                <strong>Berkas Syarat:</strong><br>
                <a href="{{ route('pengajuan.file', $pengajuan->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    Lihat Berkas
                </a>
            </div>
            <div class="mt-4">
                <a href="{{ route('pengajuan.bukti', $pengajuan->id) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i> Unduh Bukti Pengajuan (PDF)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
