@extends('client.layouts.master')

@section('title', 'Thông báo - CakeHome')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            @include('client.account.partials.sidebar')

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-bell text-primary me-2"></i>
                        Thông báo
                    </h4>
                    @if ($notifications->where('is_read', false)->count() > 0)
                        <a href="{{ route('notifications.markAllRead') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-check-all"></i> Đánh dấu đã đọc tất cả
                        </a>
                    @endif
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @forelse($notifications as $notification)
                            <div class="notification-item p-3 border-bottom {{ !$notification->is_read ? 'bg-light' : '' }}"
                                onclick="window.location.href='{{ $notification->link }}?read={{ $notification->id }}'">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="notification-icon">
                                            <i class="bi bi-bell-fill text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                            {{ $notification->message }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            @if (!$notification->is_read)
                                                <span class="badge bg-primary">Mới</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3">Không có thông báo</h5>
                                <p class="text-muted">Bạn chưa có thông báo nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if ($notifications->hasPages())
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .notification-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa !important;
        }

        .notification-item.bg-light {
            background-color: #e7f3ff !important;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e7f3ff;
            border-radius: 50%;
        }
    </style>
@endsection
