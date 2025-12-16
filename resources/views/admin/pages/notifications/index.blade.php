@extends('admin.layouts.master')

@section('title', 'Thông Báo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Thông Báo</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="page-title">
                    <i class="bi bi-bell me-2"></i>Thông Báo
                </h1>
            </div>
            <div class="col-md-6 text-end">
                @if ($notifications->where('is_read', false)->count() > 0)
                    <a href="{{ route('admin.notifications.markAllRead') }}" class="btn btn-primary">
                        <i class="bi bi-check-all me-2"></i>Đánh dấu tất cả đã đọc
                    </a>
                @endif
            </div>
        </div>
        <!-- Notifications List -->
        <div class="card">
            <div class="card-body p-0">
                @forelse ($notifications as $notification)
                    <div class="notification-item {{ $notification->is_read ? '' : 'unread' }} border-bottom">
                        <a href="{{ $notification->link }}"
                            onclick="markAsRead({{ $notification->id }}, '{{ $notification->link }}'); return false;"
                            class="text-decoration-none text-dark">
                            <div class="d-flex align-items-start p-3">
                                <div class="flex-shrink-0 me-3">
                                    @if (Str::contains($notification->type, 'order'))
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                            <i class="bi bi-cart-check fs-4 text-primary"></i>
                                        </div>
                                    @else
                                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                            <i class="bi bi-info-circle fs-4 text-info"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">
                                        {{ $notification->message }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @if (!$notification->is_read)
                                    <div class="flex-shrink-0 ms-2">
                                        <span class="badge bg-primary rounded-pill">Mới</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3 fs-5">Chưa có thông báo nào</p>
                    </div>
                @endforelse
            </div>
            @if ($notifications->hasPages())
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .notification-item.unread {
            background-color: #e3f2fd;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item {
            transition: background-color 0.2s;
        }
    </style>

    <script>
        function markAsRead(notificationId, link) {
            fetch(`/admin/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = link;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.href = link;
                });
        }
    </script>
@endsection
