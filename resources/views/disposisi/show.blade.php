@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Kolom Kiri: Detail Surat Utama -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Surat</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-1">No. Agenda</p>
                        <p class="fw-bold">{{ $disposisi->suratMasuk->no_agenda }}</p>

                        <p class="text-muted small mb-1">No. & Tgl Surat</p>
                        <p class="fw-bold">{{ $disposisi->suratMasuk->no_surat }} <br> <small
                                class="fw-normal">{{ $disposisi->suratMasuk->tanggal_surat }}</small></p>

                        <p class="text-muted small mb-1">Pengirim</p>
                        <p class="fw-bold">{{ $disposisi->suratMasuk->pengirim }}</p>

                        <p class="text-muted small mb-1">Perihal</p>
                        <p>{{ $disposisi->suratMasuk->perihal }}</p>

                        @if($disposisi->suratMasuk->file_path)
                            <div class="d-grid mt-3">
                                <a href="{{ Storage::url($disposisi->suratMasuk->file_path) }}" target="_blank"
                                    class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-download me-2"></i>Lihat File Surat
                                </a>
                            </div>
                        @else
                            <div class="alert alert-secondary py-2 small text-center mt-3">File tidak tersedia</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail Disposisi & Aksi -->
            <div class="col-md-8">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-primary">Lembar Disposisi</h4>
                            <span
                                class="badge {{ $disposisi->status == 'selesai' ? 'bg-success' : ($disposisi->status == 'diproses' ? 'bg-warning text-dark' : 'bg-secondary') }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $disposisi->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <small class="text-muted d-block uppercase tracking-wide">DARI</small>
                                <strong>{{ $disposisi->pengirim->name }}</strong> <br>
                                <span
                                    class="text-muted small">{{ strtoupper(str_replace('_', ' ', $disposisi->pengirim->role)) }}</span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted d-block uppercase tracking-wide">KEPADA</small>
                                <strong>{{ $disposisi->penerima->name }}</strong> <br>
                                <span
                                    class="text-muted small">{{ strtoupper(str_replace('_', ' ', $disposisi->penerima->role)) }}</span>
                            </div>
                        </div>

                        <div class="bg-light p-4 rounded-3 mb-4">
                            <h6 class="text-muted mb-3"><i class="fas fa-quote-left me-2"></i>Instruksi</h6>
                            <p class="lead mb-0">{{ $disposisi->instruksi }}</p>
                            @if($disposisi->catatan_tambahan)
                                <p class="mt-2 text-muted fst-italic mb-0">Catatan: {{ $disposisi->catatan_tambahan }}</p>
                            @endif
                            @if($disposisi->batas_waktu)
                                <div class="mt-3 text-danger small fw-bold">
                                    <i class="fas fa-hourglass-half me-1"></i> Batas Waktu: {{ $disposisi->batas_waktu }}
                                </div>
                            @endif
                        </div>

                        <!-- AREA AKSI untuk PENERIMA -->
                        @if(Auth::id() == $disposisi->penerima_id)

                            <!-- Jika Role Kasubbag: Tombol Teruskan -->
                            @if(Auth::user()->role == 'kasubbag')
                                <div class="alert alert-info border-0 d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-info-circle me-2"></i>Silakan teruskan disposisi ini ke Staff terkait.
                                    </div>
                                    <a href="{{ route('disposisi.create', $disposisi->surat_masuk_id) }}" class="btn btn-primary">
                                        <i class="fas fa-share me-2"></i>Teruskan Disposisi
                                    </a>
                                </div>
                            @else
                                <!-- Jika Role Staff: Form Laporan Penyelesaian -->
                                @if($disposisi->status != 'selesai')
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">Form Tindak Lanjut</div>
                                        <div class="card-body">
                                            <form action="{{ route('disposisi.update', $disposisi->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label class="form-label">Laporan / Hasil Tindak Lanjut</label>
                                                    <textarea name="laporan_penyelesaian" rows="4" class="form-control" required
                                                        placeholder="Jelaskan hasil pekerjaan..."></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Unggah Bukti / File (Opsional)</label>
                                                    <input type="file" name="file_penyelesaian" class="form-control">
                                                </div>

                                                <button type="submit" name="status" value="selesai" class="btn btn-success w-100">
                                                    <i class="fas fa-check-circle me-2"></i> Simpan & Tandai Selesai
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <strong><i class="fas fa-check-double me-2"></i>Tugas Selesai</strong>
                                        <p class="mb-2 mt-1">{{ $disposisi->laporan_penyelesaian }}</p>
                                        @if($disposisi->file_penyelesaian)
                                            <div class="mt-2 text-end">
                                                <a href="{{ Storage::url($disposisi->file_penyelesaian) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-paperclip me-1"></i> Lihat File Laporan
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif

                        @else
                            <!-- View Only untuk Pengirim/Orang lain -->
                            @if($disposisi->laporan_penyelesaian)
                                <div class="alert alert-success mt-4">
                                    <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Laporan Penyelesaian</h6>
                                    <p class="mb-2">{{ $disposisi->laporan_penyelesaian }}</p>
                                    @if($disposisi->file_penyelesaian)
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($disposisi->file_penyelesaian) }}" target="_blank"
                                                class="text-success fw-bold text-decoration-none">
                                                <i class="fas fa-paperclip me-1"></i> Lihat File Laporan
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @elseif($disposisi->status == 'diproses')
                                <div class="alert alert-warning mt-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Sedang dikerjakan oleh penerima.
                                </div>
                            @endif
                        @endif

                    </div>
                </div>

                <!-- LOG RIWAYAT UMUM -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent fw-bold">Riwayat Alur Disposisi</div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            @foreach($riwayat as $log)
                                <tr>
                                    <td class="px-4 py-3">
                                        <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small><br>
                                        <strong>{{ $log->pengirim->name }}</strong> <i
                                            class="fas fa-arrow-right mx-2 text-muted"></i>
                                        <strong>{{ $log->penerima->name }}</strong>
                                        <p class="small text-muted mb-0 mt-1">"{{ Str::limit($log->instruksi, 50) }}"</p>
                                    </td>
                                    <td class="align-middle text-end px-4">
                                        @if($log->status == 'selesai')
                                            <i class="fas fa-check-circle text-success" title="Selesai"></i>
                                        @elseif($log->status == 'diproses')
                                            <i class="fas fa-clock text-warning" title="Diproses"></i>
                                        @else
                                            <i class="fas fa-envelope text-secondary" title="Terkirim"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection