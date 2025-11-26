@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Disposisi Masuk</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Pengirim</th>
                        <th>Instruksi</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposisiMasuk as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($d->suratMasuk)->no_surat ?? '-' }}</td>
                            <td>{{ optional($d->pengirim)->name ?? '-' }}</td>
                            <td>{{ $d->instruksi }}</td>
                            <td>{{ $d->batas_waktu }}</td>
                            <td>
                                @if($d->status === 'belum_dibaca')
                                    <span class="badge bg-warning text-dark">Belum Dibaca</span>
                                @elseif($d->status === 'dibaca')
                                    <span class="badge bg-success">Dibaca</span>
                                @else
                                    <span class="badge bg-secondary">{{ $d->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada disposisi masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
