@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Arsip</h2>
                <a href="{{ route('arsip.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Arsip</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Kode Klasifikasi</div>
                        <div class="col-md-9">: {{ $arsip->kode_klasifikasi }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Lokasi Arsip</div>
                        <div class="col-md-9">: {{ $arsip->lokasi_arsip }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tanggal Arsip</div>
                        <div class="col-md-9">: {{ \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d F Y') }}</div>
                    </div>
                    
                    <hr>
                    <h6 class="fw-bold mb-3">Informasi Surat Terkait</h6>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Jenis Surat</div>
                        <div class="col-md-9">
                            : 
                            @if($arsip->surat_type == 'App\Models\SuratMasuk')
                                <span class="badge bg-primary">Surat Masuk</span>
                            @else
                                <span class="badge bg-success">Surat Keluar</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">No Surat</div>
                        <div class="col-md-9">: {{ $arsip->surat->no_surat ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Perihal</div>
                        <div class="col-md-9">: {{ $arsip->surat->perihal ?? '-' }}</div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
