@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Buat Disposisi</h2>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Informasi Surat</h5>
            <p><strong>No Surat:</strong> {{ $surat->no_surat }}</p>
            <p><strong>Pengirim:</strong> {{ $surat->pengirim }}</p>
            <p><strong>Perihal:</strong> {{ $surat->perihal }}</p>

            <hr>

            <form action="{{ route('disposisi.store') }}" method="POST">
                @csrf

                <input type="hidden" name="surat_masuk_id" value="{{ $surat->id }}">

                <div class="mb-3">
                    <label class="form-label">Teruskan Kepada</label>
                    <select name="penerima_id" class="form-select @error('penerima_id') is-invalid @enderror" required>
                        <option value="" disabled selected>Pilih Tujuan</option>
                        @foreach($tujuan as $user)
                            <option value="{{ $user->id }}" {{ old('penerima_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('penerima_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Instruksi</label>
                    <textarea name="instruksi" rows="3" class="form-control @error('instruksi') is-invalid @enderror" required>{{ old('instruksi') }}</textarea>
                    @error('instruksi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Batas Waktu</label>
                    <input type="date" name="batas_waktu" value="{{ old('batas_waktu') }}" class="form-control @error('batas_waktu') is-invalid @enderror">
                    @error('batas_waktu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Kirim Disposisi</button>
            </form>
        </div>
    </div>
</div>
@endsection
