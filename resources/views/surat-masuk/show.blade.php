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
            
            {{-- Form Teruskan (Khusus Staff & Posisi Staff) --}}
            @if(auth()->user()->role == 'staff' && $suratMasuk->posisi == 'staff')
            <div class="card shadow-sm mb-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-share me-2"></i>Teruskan Surat</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat-masuk.forward', $suratMasuk->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tujuan_role" class="form-label fw-bold">Teruskan Kepada:</label>
                            <select name="tujuan_role" class="form-select" required>
                                <option value="" disabled selected>Pilih Pimpinan...</option>
                                <option value="kepala_unit">Kepala Unit</option>
                                <option value="kasubbag">Kepala Bagian (Kasubbag)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim / Teruskan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Riwayat Surat (Hanya muncul jika status Selesai) --}}
            @if($suratMasuk->status == 'selesai')
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Digital Surat</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal & Waktu</th>
                                    <th>Dari</th>
                                    <th>Kepada</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1. Log Pembuatan Surat --}}
                                <tr>
                                    <td>{{ $suratMasuk->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $suratMasuk->user->name ?? 'Staff' }}</td>
                                    <td>-</td>
                                    <td>Surat Masuk Dibuat</td>
                                    <td><span class="badge bg-secondary">Baru</span></td>
                                </tr>

                                {{-- 2. Log Disposisi --}}
                                @foreach($suratMasuk->disposisis as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $log->pengirim->name ?? '-' }}</td>
                                        <td>{{ $log->penerima->name ?? '-' }}</td>
                                        <td>{{ $log->instruksi }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">Didisposisikan</span>
                                            @if($log->catatan_tambahan)
                                                <div class="small text-muted mt-1">{{ $log->catatan_tambahan }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- 3. Log Selesai --}}
                                <tr class="table-success">
                                    <td>{{ $suratMasuk->updated_at->format('d/m/Y H:i') }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>Proses Selesai</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif


        </div>
    </div>
</div>
@endsection
