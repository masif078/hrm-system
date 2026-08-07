@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-dark fw-bold">Notifications Center</h2>
            <p class="text-muted mb-0">View and manage your real-time system alerts</p>
        </div>
        @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="bi bi-check2-all me-1"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0 mb-4">
        <div class="card-body p-0">
            <div class="list-group list-group-flush rounded-3">
                @forelse($notifications as $notification)
                    <div class="list-group-item p-4 d-flex align-items-start {{ $notification->read() ? '' : 'bg-light border-start border-primary border-4' }}">
                        <div class="me-3 mt-1">
                            @if(($notification->data['type'] ?? '') === 'leave_applied')
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-calendar-event fs-5"></i>
                                </div>
                            @elseif(($notification->data['type'] ?? '') === 'leave_status')
                                @if(($notification->data['status'] ?? '') === 'Approved')
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-calendar-check fs-5"></i>
                                    </div>
                                @else
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-calendar-x fs-5"></i>
                                    </div>
                                @endif
                            @elseif(($notification->data['type'] ?? '') === 'task_assigned')
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-clipboard-check fs-5"></i>
                                </div>
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-bell fs-5"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-dark">{{ $notification->data['title'] ?? 'System Alert' }}</h6>
                                <small class="text-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-muted mb-2" style="font-size: 0.95rem;">{{ $notification->data['message'] ?? '' }}</p>
                            
                            <div class="d-flex gap-2">
                                @if(!$notification->read())
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary py-1 px-3">
                                            <i class="bi bi-check-lg me-1"></i> Mark Read
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-3">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-bell-slash fs-1 d-block mb-3 text-secondary"></i>
                        <h4 class="fw-bold">All caught up!</h4>
                        <p class="mb-0">You have no notifications in your inbox.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
