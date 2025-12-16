@php
    $notifications = auth()->check() ? auth()->user()->notifications()->latest()->limit(5)->get() : collect();
    $unreadCount = auth()->check() ? auth()->user()->notifications()->where('is_read', false)->count() : 0;
@endphp

<li class="nav-item dropdown notifications-dropdown" style="list-style: none;">
    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        @if ($unreadCount > 0)
            <span
                class="position-absolute top-0 start-100 mt-1 translate-middle badge rounded-pill bg-danger notification-badge">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @else
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
                style="display: none;">
                0
            </span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end notification-dropdown notifications-dropdown-content"
        aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Thông báo</span>
            @if ($unreadCount > 0)
                <a href="{{ route('notifications.markAllRead') }}" class="text-primary small">
                    Đánh dấu đã đọc tất cả
                </a>
            @endif
            @if ($notifications->count() > 0)
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary">
                        Xem tất cả thông báo
                    </a>
                </li>
            @endif
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>

        @forelse($notifications as $notification)
            <li>
                <a href="{{ $notification->link }}?read={{ $notification->id }}"
                    class="dropdown-item py-3 {{ !$notification->is_read ? 'bg-light' : '' }}"
                    onclick="markAsRead({{ $notification->id }})">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-bell-fill text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                {{ $notification->message }}
                            </p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
            </li>
            @if (!$loop->last)
                <li>
                    <hr class="dropdown-divider m-0">
                </li>
            @endif
        @empty
            <li class="text-center py-4 text-muted">
                <i class="bi bi-bell-slash fs-3"></i>
                <p class="mb-0 mt-2">Không có thông báo mới</p>
            </li>
        @endforelse

        
    </ul>
</li>

<style>
    .notification-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .notification-dropdown .dropdown-item.bg-light {
        background-color: #e7f3ff !important;
    }

    /* Wrap long messages inside dropdown */
    .notification-dropdown .dropdown-item p {
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
</style>

<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }
</script>
