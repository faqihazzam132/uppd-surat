@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Pengguna</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-3">
                Kelola akun pengguna internal (Admin, Staff, Kepala Unit, Kasubbag, Pemohon).
            </p>

            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIK</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nik }}</td>
                            <td>{{ $user->no_hp }}</td>
                            <td>
                                @php
                                    $role = $user->role;
                                    $roleLabel = ucfirst(str_replace('_', ' ', $role));
                                    $roleClass = match($role) {
                                        'admin' => 'bg-primary',
                                        'staff' => 'bg-info text-dark',
                                        'kepala_unit' => 'bg-warning text-dark',
                                        'kasubbag' => 'bg-secondary',
                                        'pemohon' => 'bg-success',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <a href="{{ route('admin.users.reset-password', $user) }}" class="btn btn-sm btn-secondary me-1">
                                    <i class="fas fa-key me-1"></i> Reset Password
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
