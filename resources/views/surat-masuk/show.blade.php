@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Surat Masuk</h2>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <strong>No Agenda:</strong><br>
                {{ $suratMasuk->no_agenda }}
            </div>
            <div class="mb-3">
                <strong>No Surat:</strong><br>
                {{ $suratMasuk->no_surat }}
            </div>
            <div class="mb-3">
                <strong>Tanggal Surat:</strong><br>
                {{ $suratMasuk->tanggal_surat }}
            </div>
            <div class="mb-3">
                <strong>Tanggal Diterima:</strong><br>
                {{ $suratMasuk->tanggal_diterima }}
            </div>
            <div class="mb-3">
                <strong>Pengirim:</strong><br>
                {{ $suratMasuk->pengirim }}
            </div>
            <div class="mb-3">
                <strong>Perihal:</strong><br>
                {{ $suratMasuk->perihal }}
            </div>
            <div class="mb-3">
                <strong>Sifat:</strong><br>
                {{ $suratMasuk->sifat }}
            </div>
            <div class="mb-3">
                <strong>File Surat:</strong><br>
                <a href="{{ asset('storage/'.$suratMasuk->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    Lihat File
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
