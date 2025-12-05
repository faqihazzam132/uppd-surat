@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Arsipkan Surat</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Mengarsipkan Surat:</strong> {{ $surat->no_surat }} <br>
                        <strong>Perihal:</strong> {{ $surat->perihal }}
                    </div>

                    <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="surat_type" value="{{ $type }}">
                        <input type="hidden" name="surat_id" value="{{ $id }}">

                        <div class="mb-3">
                            <label for="kode_klasifikasi" class="form-label">Kode Klasifikasi</label>
                            <input type="text" class="form-control @error('kode_klasifikasi') is-invalid @enderror" id="kode_klasifikasi" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}" required>
                            @error('kode_klasifikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lokasi_arsip" class="form-label">Lokasi Arsip (Rak/Lemari/Box)</label>
                            <input type="text" class="form-control @error('lokasi_arsip') is-invalid @enderror" id="lokasi_arsip" name="lokasi_arsip" value="{{ old('lokasi_arsip') }}" required>
                            @error('lokasi_arsip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_arsip" class="form-label">Tanggal Arsip</label>
                            <input type="date" class="form-control @error('tanggal_arsip') is-invalid @enderror" id="tanggal_arsip" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}" required>
                            @error('tanggal_arsip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Arsip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
