@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">Layanan Pelacakan Surat</h1>
                <p class="lead text-muted">Pantau status surat atau permohonan Anda secara real-time dengan memasukkan Nomor
                    Agenda atau Kode Registrasi yang Anda miliki.</p>
            </div>

            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <form action="{{ route('tracking.search') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="keyword"
                                    class="form-label fw-bold text-uppercase text-secondary tracking-wide">Nomor Agenda /
                                    No. Registrasi</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-search text-primary"></i></span>
                                    <input type="text" name="keyword" id="keyword" class="form-control border-start-0 ps-0"
                                        placeholder="Contoh: 12345/SM/2025" required>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">Lacak Status</button>
                                </div>
                                <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> Masukkan nomor yang
                                    tertera pada tanda terima surat Anda.</div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="text-muted small">
                        &copy; {{ date('Y') }} UPPD Kecamatan Kalideres. <br>
                        Jl. Peta Selatan No. 24, Kalideres, Jakarta Barat.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection