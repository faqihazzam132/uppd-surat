@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Lembar Disposisi</h5>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-light text-primary">Kembali</a>
                    </div>
                    <div class="card-body">

                        <!-- Informasi Surat -->
                        <div class="alert alert-info border-0">
                            <div class="row">
                                <div class="col-md-6 mb-2"><strong>No. Surat:</strong> {{ $surat->no_surat }}</div>
                                <div class="col-md-6 mb-2"><strong>Tanggal Surat:</strong> {{ $surat->tanggal_surat }}</div>
                                <div class="col-md-6 mb-2"><strong>Pengirim:</strong> {{ $surat->pengirim }}</div>
                                <div class="col-md-6 mb-2"><strong>Sifat:</strong> <span
                                        class="badge bg-secondary">{{ ucfirst($surat->sifat) }}</span></div>
                                <div class="col-12 mt-2">
                                    <strong>Perihal:</strong>
                                    <p class="mb-0">{{ $surat->perihal }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Instruksi Sebelumnya (Jika ada) -->
                        @if($surat->disposisis->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-muted border-bottom pb-2">Riwayat Instruksi</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($surat->disposisis as $prev)
                                        <li class="list-group-item bg-transparent px-0">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ $prev->pengirim->name }} <i
                                                        class="fas fa-arrow-right mx-1 text-muted"></i>
                                                    {{ $prev->penerima->name }}</strong>
                                                <small class="text-muted">{{ $prev->created_at->format('d M Y H:i') }}</small>
                                            </div>
                                            <p class="mb-0 text-dark fst-italic">"{{ $prev->instruksi }}"</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr>

                        <!-- Form Input Disposisi -->
                        <form action="{{ route('disposisi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="surat_masuk_id" value="{{ $surat->id }}">

                            <div class="mb-3">
                                <label for="penerima_id" class="form-label fw-bold">Diteruskan Kepada (Penerima)</label>
                                <select name="penerima_id" id="penerima_id" class="form-select" required>
                                    <option value="">-- Pilih Penerima --</option>
                                    @foreach($tujuan as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} -
                                            {{ strtoupper(str_replace('_', ' ', $user->role)) }}</option>
                                    @endforeach
                                </select>
                                @error('penerima_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="instruksi" class="form-label fw-bold">Isi Instruksi / Arahan</label>
                                <textarea name="instruksi" id="instruksi" rows="4" class="form-control"
                                    placeholder="Tuliskan instruksi jelas kepada penerima..." required></textarea>
                                @error('instruksi') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="batas_waktu" class="form-label fw-bold">Batas Waktu (Opsional)</label>
                                    <input type="date" name="batas_waktu" id="batas_waktu" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="catatan_tambahan" class="form-label fw-bold">Catatan Tambahan</label>
                                    <input type="text" name="catatan_tambahan" id="catatan_tambahan" class="form-control"
                                        placeholder="Contoh: Segera tindak lanjuti">
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Disposisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection