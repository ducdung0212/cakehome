@php
    $adminUser = Auth::guard('admin')->user();

    $adminNotifications =
        $adminUser && $adminUser->role && in_array($adminUser->role->name, ['admin', 'staff'])
            ? App\Models\Notification::where('user_id', $adminUser->id)->orderBy('created_at', 'desc')->limit(5)->get()
            : collect([]);

    // Đếm unread count từ toàn bộ notifications (không giới hạn 5 cái hiển thị)
    $totalUnreadCount =
        $adminUser && $adminUser->role && in_array($adminUser->role->name, ['admin', 'staff'])
            ? App\Models\Notification::where('user_id', $adminUser->id)->where('is_read', false)->count()
            : 0;
@endphp

<li class="nav-item dropdown notifications-dropdown" id="notificationDropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        @if ($totalUnreadCount > 0)
            <span
                class="position-absolute top-0 start-100 mt-2 translate-middle badge rounded-pill bg-danger notification-badge"
                style="font-size: 0.65rem; padding: 0.25rem 0.5rem;">
                {{ $totalUnreadCount > 99 ? '99+' : $totalUnreadCount }}
            </span>
        @else
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
                style="font-size: 0.65rem; padding: 0.25rem 0.5rem; display: none;">
                0
            </span>
        @endif
        
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow notifications-dropdown-content"
        style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Thông Báo</span>
            @if ($totalUnreadCount > 0)
                <a href="{{ route('admin.notifications.markAllRead') }}"
                    class="btn btn-sm btn-link text-decoration-none" style="font-size: 0.75rem;">
                    Đánh dấu tất cả
                </a>
            @endif
            @if ($adminNotifications->isNotEmpty())
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-center text-primary fw-bold"
                        href="{{ route('admin.notifications.index') }}">
                        Xem tất cả thông báo
                    </a>
                </li>
             @endif
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        @forelse ($adminNotifications as $notification)
            <li>
                <a class="dropdown-item notification-item {{ $notification->is_read ? '' : 'unread' }}"
                    href="{{ $notification->link }}"
                    onclick="markAsRead({{ $notification->id }}, '{{ $notification->link }}'); return false;">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            @if (Str::contains($notification->type, 'order'))
                                <i class="bi bi-cart-check text-primary"></i>
                            @else
                                <i class="bi bi-info-circle text-info"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-1" style="font-size: 0.875rem;">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
            </li>
            @if (!$loop->last)
                <li>
                    <hr class="dropdown-divider">
                </li>
            @endif
        @empty
            <li class="text-center py-3 text-muted">
                <i class="bi bi-bell-slash" style="font-size: 2rem;"></i>
                <p class="mb-0 mt-2">Chưa có thông báo</p>
            </li>
        @endforelse
        
    </ul>
</li>

<style>
    .notification-item.unread {
        background-color: #e3f2fd;
        font-weight: 500;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    /* Wrap long messages inside dropdown */
    .notification-item p {
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
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
