@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Surat Masuk</h2>
        <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Surat Masuk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Agenda</th>
                            <th>No. Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Pengirim</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surats as $surat)
                            <tr>
                                <td>{{ $surat->no_agenda }}</td>
                                <td>{{ $surat->no_surat }}</td>
                                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                <td>{{ $surat->pengirim }}</td>
                                <td>{{ $surat->perihal }}</td>
                                <td>
                                    @if($surat->status == 'menunggu_disposisi')
                                        <span class="badge bg-warning">Menunggu Disposisi</span>
                                    @elseif($surat->status == 'diproses')
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($surat->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('surat-masuk.show', $surat->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('surat-masuk.disposisi.create', $surat->id) }}" class="btn btn-sm btn-primary" title="Buat Disposisi">
                                        <i class="fas fa-share"></i>
                                    </a>
                                    <a href="{{ route('surat-masuk.download', $surat->id) }}" class="btn btn-sm btn-secondary" title="Unduh Berkas">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data surat masuk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $surats->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
