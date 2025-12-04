@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Arsip Surat</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Klasifikasi</th>
                        <th>Lokasi Arsip</th>
                        <th>Tanggal Arsip</th>
                        <th>No Surat</th>
                        <th>Jenis Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsips as $arsip)
                        <tr>
                            <td>{{ $arsip->kode_klasifikasi }}</td>
                            <td>{{ $arsip->lokasi_arsip }}</td>
                            <td>{{ $arsip->tanggal_arsip }}</td>
                            <td>{{ $arsip->surat->no_surat ?? '-' }}</td>
                            <td>
                                @if($arsip->surat_type == 'App\Models\SuratMasuk')
                                    <span class="badge bg-primary">Surat Masuk</span>
                                @else
                                    <span class="badge bg-success">Surat Keluar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('arsip.show', $arsip->id) }}" class="btn btn-sm btn-info text-white">
                                    Detail
                                </a>
                                <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada data arsip.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
