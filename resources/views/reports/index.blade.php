@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i>Laporan & Rekapitulasi</h2>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Filter Laporan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reports.generate') }}" method="POST" target="_blank">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Laporan</label>
                                <select name="jenis_laporan" class="form-select" required>
                                    <option value="surat_masuk">Rekapitulasi Surat Masuk</option>
                                    <option value="surat_keluar">Rekapitulasi Surat Keluar</option>
                                </select>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Dari Tanggal</label>
                                    <input type="date" name="start_date" class="form-control" required
                                        value="{{ date('Y-m-01') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Sampai Tanggal</label>
                                    <input type="date" name="end_date" class="form-control" required
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="preview"
                                    class="btn btn-info text-white flex-grow-1">
                                    <i class="fas fa-eye me-2"></i> Pratinjau (Web)
                                </button>
                                <button type="submit" name="action" value="pdf" class="btn btn-danger flex-grow-1">
                                    <i class="fas fa-file-pdf me-2"></i> Export PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100 bg-light border-0">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <i class="fas fa-print fa-5x text-muted mb-3 opacity-25"></i>
                        <h5 class="text-muted">Instruksi</h5>
                        <p class="text-muted small">Pilih jenis laporan dan rentang tanggal untuk menghasilkan rekapitulasi
                            surat. <br> Anda dapat melihat pratinjau di browser atau mengunduh langsung dalam format PDF.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection