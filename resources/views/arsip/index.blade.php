@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Arsip Surat</h2>
    </div>

    {{-- Form Pencarian & Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('arsip.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari (No Surat / Perihal / Pengirim / Tujuan)</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                    </div>
                    <div class="col-md-2">
                        <label for="jenis_surat" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="jenis_surat" name="jenis_surat">
                            <option value="">Semua</option>
                            <option value="masuk" {{ request('jenis_surat') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="keluar" {{ request('jenis_surat') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_awal" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
                        <a href="{{ route('arsip.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
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
