@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-bell me-2"></i>Notifikasi Saya</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                Tandai semua sudah dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" 
                           class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notification->data['message'] ?? 'Notifikasi' }}</h6>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!empty($notification->data['no_registrasi']))
                                <p class="mb-1">No. Registrasi: <strong>{{ $notification->data['no_registrasi'] }}</strong></p>
                            @endif
                            @if(!$notification->read_at)
                                <span class="badge bg-primary">Baru</span>
                            @endif
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <p class="mb-0">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
