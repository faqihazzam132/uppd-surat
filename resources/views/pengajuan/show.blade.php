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
                @php
                    $files = $pengajuan->file_syarat;
                    // Fallback untuk data lama (string path) jika casting gagal/belum JSON
                    if (empty($files) && $pengajuan->getRawOriginal('file_syarat')) {
                        $files = [$pengajuan->getRawOriginal('file_syarat')];
                    }
                    if (is_string($files)) {
                        $files = [$files];
                    }
                @endphp

                @if(!empty($files))
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($files as $index => $file)
                            <a href="{{ route('pengajuan.file', ['id' => $pengajuan->id, 'index' => $index]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-download me-1"></i> File {{ $index + 1 }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted fst-italic">Tidak ada dokumen dilampirkan.</span>
                @endif
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
