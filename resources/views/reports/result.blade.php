@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm mb-2"><i
                        class="fas fa-arrow-left me-1"></i> Kembali</a>
                <h4>{{ $title }}</h4>
                <p class="text-muted mb-0">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print me-2"></i> Cetak Halaman
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                @if($jenis == 'surat_masuk')
                                    <th>No. Agenda</th>
                                    <th>No. Surat</th>
                                    <th>Tgl Surat</th>
                                    <th>Pengirim</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                @else
                                    <th>No. Surat</th>
                                    <th>Tgl Surat</th>
                                    <th>Tujuan</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    @if($jenis == 'surat_masuk')
                                        <td>{{ $item->no_agenda }}</td>
                                        <td>{{ $item->no_surat }}</td>
                                        <td>{{ $item->tanggal_surat }}</td>
                                        <td>{{ $item->pengirim }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                    @else
                                        <td>{{ $item->no_surat }}</td>
                                        <td>{{ $item->tanggal_surat }}</td>
                                        <td>{{ $item->tujuan }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted small">
                Dicetak pada: {{ date('d-m-Y H:i') }} oleh {{ auth()->user()->name }}
            </div>
        </div>
    </div>
@endsection