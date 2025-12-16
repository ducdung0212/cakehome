@extends('admin.layouts.master')

@section('title', 'Quản Lý Đánh Giá')

@section('breadcrumb')
    <li class="breadcrumb-item active">Đánh Giá</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Quản Lý Đánh Giá</h1>
            </div>
        </div>

        <!-- Review Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $stats['total'] }}</h3>
                        <p class="mb-0">Tổng Đánh Giá</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning">{{ $stats['average_rating'] }} <i class="bi bi-star-fill"></i></h3>
                        <p class="mb-0">Điểm Trung Bình</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-info">{{ $stats['pending'] }}</h3>
                        <p class="mb-0">Chờ Duyệt</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ $stats['approved'] }}</h3>
                        <p class="mb-0">Đã Duyệt</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Phân Bố Đánh Giá</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <div class="display-4 text-warning fw-bold">{{ $stats['average_rating'] }}</div>
                        <div class="text-warning mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($stats['average_rating']))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - 0.5 <= $stats['average_rating'])
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-muted">{{ $stats['total'] }} đánh giá</div>
                    </div>
                    <div class="col-md-10">
                        @foreach ($ratingDistribution as $rating => $data)
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $rating }} <i
                                            class="bi bi-star-fill text-warning"></i></span>
                                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                        <div class="progress-bar 
                                            @if ($rating == 5) bg-success
                                            @elseif($rating >= 4) bg-info
                                            @elseif($rating >= 3) bg-warning
                                            @else bg-danger @endif"
                                            style="width: {{ $data['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-muted">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.reviews.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="rating" class="form-select">
                                <option value="">Tất cả đánh giá</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} sao
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã từ chối
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Tìm
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="card">
            <div class="card-body">
                <div class="reviews-list">
                    @forelse($reviews as $review)
                        <div class="review-item mb-4 pb-4 border-bottom">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex mb-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=6366f1&color=fff"
                                            alt="User" class="rounded-circle me-3" width="50" height="50">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $review->user->name ?? 'Người dùng' }}</h6>
                                                    <div class="text-warning mb-1">
                                                        {!! $review->getStarsHtml() !!}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if ($review->isPending())
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                @elseif($review->isApproved())
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                @else
                                                    <span class="badge bg-danger">Từ chối</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ $review->product->name ?? 'Sản phẩm không xác định' }}</strong>
                                    </div>
                                    <p class="mb-2">{{ $review->comment }}</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    @if ($review->isPending())
                                        <div class="btn-group mb-2">
                                            <form action="{{ route('admin.reviews.approve', $review->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Duyệt đánh giá này?')">
                                                    <i class="bi bi-check-circle me-1"></i>Duyệt
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reviews.reject', $review->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Từ chối đánh giá này?')">
                                                    <i class="bi bi-x-circle me-1"></i>Từ chối
                                                </button>
                                            </form>
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.reviews.destroy') }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Xóa đánh giá này?')">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($review->isRejected())
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm"
                                                onclick="return confirm('Duyệt lại đánh giá này?')">
                                                <i class="bi bi-arrow-clockwise me-1"></i>Duyệt lại
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.destroy') }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="review_id" value="{{ $review->id }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Xóa đánh giá này?')">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm"
                                                onclick="return confirm('Ẩn đánh giá này?')">
                                                <i class="bi bi-eye-slash me-1"></i>Ẩn
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.destroy') }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="review_id" value="{{ $review->id }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Xóa đánh giá này?')">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-star" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Chưa có đánh giá nào</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .review-item:last-child {
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }
    </style>
@endpush
