@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('tracking.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <h4 class="mb-0 text-muted">Hasil Pelacakan</h4>
                </div>

                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white p-4 text-center">
                        <h5 class="mb-2 text-white-50">STATUS DOKUMEN</h5>
                        <div class="display-6 fw-bold">
                            @if($type == 'surat')
                                @php
                                    $statusMap = [
                                        'menunggu_disposisi' => 'DITERIMA',
                                        'disposisi' => 'DALAM PROSES',
                                        'selesai' => 'SELESAI',
                                    ];
                                    $label = $statusMap[$data->status] ?? $data->status;
                                @endphp
                                {{ strtoupper($label) }}
                            @else
                                @php
                                    $statusMap = [
                                        'menunggu_verifikasi' => 'MENUNGGU VERIFIKASI',
                                        'diterima' => 'DITERIMA',
                                        'ditolak' => 'DITOLAK',
                                        'selesai' => 'SELESAI',
                                    ];
                                    $label = $statusMap[$data->status] ?? $data->status;
                                @endphp
                                {{ strtoupper($label) }}
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-5">

                        <!-- Timeline Visual Sederhana -->
                        <div class="position-relative mb-5 mx-4">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $data->status == 'selesai' ? '100%' : ($data->status == 'disposisi' || $data->status == 'diterima' || $data->status == 'diproses' ? '50%' : '15%') }};"
                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-n2">
                                <span class="bg-success rounded-circle d-inline-block"
                                    style="width: 15px; height: 15px; margin-top: -6px;"></span>
                                <span
                                    class="{{ ($data->status == 'disposisi' || $data->status == 'diterima' || $data->status == 'selesai') ? 'bg-success' : 'bg-light border' }} rounded-circle d-inline-block"
                                    style="width: 15px; height: 15px; margin-top: -6px;"></span>
                                <span
                                    class="{{ $data->status == 'selesai' ? 'bg-success' : 'bg-light border' }} rounded-circle d-inline-block"
                                    style="width: 15px; height: 15px; margin-top: -6px;"></span>
                            </div>
                            <div class="d-flex justify-content-between mt-2 small text-muted text-uppercase fw-bold">
                                <span>Diterima</span>
                                <span>Proses</span>
                                <span>Selesai</span>
                            </div>
                        </div>

                        <!-- Detail Data (FR-P-04: Non-Sensitif) -->
                        <div class="row g-4">
                            @if($type == 'surat')
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">NOMOR AGENDA</small>
                                    <span class="fs-5 fw-bold">{{ $data->no_agenda }}</span>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted d-block mb-1">TANGGAL DITERIMA</small>
                                    <span
                                        class="fs-5">{{ \Carbon\Carbon::parse($data->tanggal_diterima)->format('d F Y') }}</span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted d-block mb-1">DARI PENGIRIM</small>
                                    <span class="fs-5">{{ $data->pengirim }}</span>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted d-block mb-1">JENIS / PERIHAL (RINGKASAN)</small>
                                    <span class="fs-5">{{ Str::limit($data->perihal, 100) }}</span>
                                    <!-- Membatasi perihal untuk privasi jika terlalu panjang -->
                                </div>
                            @else
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">NO. REGISTRASI</small>
                                    <span class="fs-5 fw-bold">{{ $data->no_registrasi }}</span>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted d-block mb-1">TANGGAL PENGAJUAN</small>
                                    <span class="fs-5">{{ $data->created_at->format('d F Y') }}</span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted d-block mb-1">JENIS LAYANAN</small>
                                    <span class="fs-5">{{ $data->jenis_surat }}</span>
                                </div>
                                <div class="col-md-12">
                                    <div
                                        class="alert {{ $data->status == 'ditolak' ? 'alert-danger' : 'alert-info' }} mb-0 mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        @if($data->status == 'menunggu_verifikasi')
                                            Berkas Anda sedang dalam antrean verifikasi petugas.
                                        @elseif($data->status == 'diterima')
                                            Berkas telah diterima dan sedang diproses lebih lanjut.
                                        @elseif($data->status == 'selesai')
                                            Dokumen Anda telah selesai diproses. Silakan login untuk mengunduh hasil jika tersedia.
                                        @elseif($data->status == 'ditolak')
                                            Maaf, pengajuan ditolak. Silakan login untuk melihat detail kekurangan berkas.
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer bg-light text-center py-3">
                        <small class="text-muted">Untuk informasi lebih detail, silakan hubungi petugas loket kami.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection