@extends('admin.layouts.master')
<link rel="stylesheet" href="{{ asset('assets/admin/css/order-detail.css') }}">
@section('title', 'Chi Tiết Đơn Hàng #' . $order->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn Hàng</a></li>
    <li class="breadcrumb-item active">Chi Tiết #{{ $order->id }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="page-title">
                    <i class="bi bi-receipt me-2"></i>Đơn Hàng #{{ $order->id }}
                </h1>
                <p class="text-muted">Đặt ngày {{ $order->created_at->format('d/m/Y') }} lúc
                    {{ $order->created_at->format('H:i') }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay Lại
                </a>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>In Đơn
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-cart-check text-primary me-2"></i>
                            Sản Phẩm Đã Đặt
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">Hình Ảnh</th>
                                        <th width="35%">Sản Phẩm</th>
                                        <th width="15%" class="text-center">Đơn Giá</th>
                                        <th width="15%" class="text-center">Số Lượng</th>
                                        <th width="20%" class="text-end">Thành Tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                @if ($item->product && $item->product->images->first())
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image) }}"
                                                        alt="{{ $item->product->name }}" class="img-thumbnail"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">
                                                    {{ $item->product->name ?? 'Sản phẩm không xác định' }}
                                                </div>
                                                @if ($item->product && $item->product->category)
                                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">{{ number_format($item->price) }}đ</td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-light text-dark">x{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end align-middle fw-bold text-primary">
                                                {{ number_format($item->price * $item->quantity) }}đ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-sm mb-0 table-borderless">
                                    <tr>
                                        <td class="text-muted">Tạm tính:</td>
                                        <td class="text-end">{{ number_format($order->subtotal_price) }}đ</td>
                                    </tr>
                                    @if ($order->discount_amount > 0)
                                        <tr>
                                            <td class="text-muted">Giảm giá:</td>
                                            <td class="text-end text-success">
                                                -{{ number_format($order->discount_amount) }}đ</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-muted">Phí vận chuyển:</td>
                                        <td class="text-end">
                                            @if ($order->delivery_method === 'delivery')
                                                {{ number_format(30000) }}đ
                                            @else
                                                <span class="text-success">Miễn phí</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold fs-5">Tổng cộng:</td>
                                        <td class="text-end fw-bold text-danger fs-5">
                                            {{ number_format($order->total_price) }}đ</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Lịch Sử Đơn Hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($order->orderStatusHistories as $index => $history)
                                <div class="timeline-item {{ $index === 0 ? 'active' : '' }}">
                                    <div
                                        class="timeline-marker 
                                        @if ($history->status === 'pending') bg-warning
                                        @elseif($history->status === 'confirmed') bg-info
                                        @elseif($history->status === 'processing') bg-primary
                                        @elseif($history->status === 'ready') bg-info
                                        @elseif($history->status === 'shipping') bg-secondary
                                        @elseif($history->status === 'delivered') bg-success
                                        @elseif($history->status === 'completed') bg-success
                                        @else bg-danger @endif">
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-title">
                                            @if ($history->status === 'pending')
                                                Đơn hàng đang chờ xác nhận
                                            @elseif($history->status === 'confirmed')
                                                Đã xác nhận đơn hàng
                                            @elseif($history->status === 'processing')
                                                Đang chuẩn bị hàng
                                            @elseif($history->status === 'ready')
                                                Đã chuẩn bị xong
                                            @elseif($history->status === 'shipping')
                                                Đang giao hàng
                                            @elseif($history->status === 'delivered')
                                                Đã giao hàng
                                            @elseif($history->status === 'completed')
                                                Hoàn thành
                                            @else
                                                Đã hủy
                                            @endif
                                        </div>
                                        <div class="timeline-time">{{ $history->created_at->format('d/m/Y H:i') }}</div>
                                        @if ($history->notes)
                                            <div class="timeline-desc">{{ $history->notes }}</div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Chưa có lịch sử cập nhật</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Status -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Cập Nhật Trạng Thái
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái hiện tại</label>
                                <div>
                                    @if ($order->status === 'pending')
                                        <span class="badge bg-warning fs-6">Chờ xác nhận</span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="badge bg-info fs-6">Đã xác nhận</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge bg-primary fs-6">Đang chuẩn bị</span>
                                    @elseif($order->status === 'ready')
                                        <span class="badge bg-info fs-6">Đã chuẩn bị</span>
                                    @elseif($order->status === 'shipping')
                                        <span class="badge bg-secondary fs-6">Đang giao hàng</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="badge bg-success fs-6">Đã giao hàng</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge bg-success fs-6">Hoàn thành</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Đã hủy</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cập nhật trạng thái</label>
                                <select class="form-select" name="status" required>
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                        Chờ xác nhận</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>
                                        Đã xác nhận</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                        Đang chuẩn bị</option>
                                    <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>
                                        Đã chuẩn bị</option>
                                    @if ($order->delivery_method === 'delivery')
                                        <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>
                                            Đang giao hàng</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                            Đã giao hàng</option>
                                    @endif
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                        Hoàn thành</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                        Đã hủy</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ghi chú</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Thêm ghi chú về đơn hàng..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle me-2"></i>Cập Nhật
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Thông Tin Khách Hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Tên khách hàng</div>
                            <div class="fw-bold">{{ $order->user->name }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Email</div>
                            <div>{{ $order->user->email }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Điện thoại</div>
                            <div>{{ $order->user->phone_number ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Trạng thái tài khoản</div>
                            <div>
                                @if ($order->user->status === 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($order->user->status === 'pending')
                                    <span class="badge bg-warning">Chờ kích hoạt</span>
                                @else
                                    <span class="badge bg-danger">Bị cấm</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.users.show', $order->user->id) }}"
                            class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-eye me-2"></i>Xem Hồ Sơ
                        </a>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-geo-alt me-2"></i>
                            Địa Chỉ Giao Hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($order->shippingAddress)
                            <div class="mb-2">
                                <strong>{{ $order->shippingAddress->full_name }}</strong>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-telephone me-1"></i>{{ $order->shippingAddress->phone_number }}
                            </div>
                            <div class="text-muted">
                                <i class="bi bi-house-door me-1"></i>
                                {{ $order->shippingAddress->address }},
                                {{ $order->shippingAddress->ward }},
                                {{ $order->shippingAddress->district }},
                                {{ $order->shippingAddress->province }}
                            </div>
                        @else
                            <p class="text-muted mb-0">Chưa có địa chỉ giao hàng</p>
                        @endif

                        @if ($order->notes)
                            <hr>
                            <div>
                                <small class="text-muted fw-bold">Ghi chú từ khách hàng:</small>
                                <p class="mb-0 mt-1">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-truck me-2"></i>
                            Thông Tin Giao Hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Phương thức</div>
                            <div>
                                @if ($order->delivery_method === 'delivery')
                                    <span class="badge bg-info">Giao hàng tận nơi</span>
                                @else
                                    <span class="badge bg-secondary">Lấy tại cửa hàng</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Hẹn giao lúc</div>
                            <div class="text-primary">
                                <i class="bi bi-clock me-1"></i>
                                @if ($order->delivery_at)
                                    {{ $order->delivery_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            Thông Tin Thanh Toán
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($order->payment)
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Phương thức</div>
                                <div>
                                    @if ($order->payment->payment_method === 'cash')
                                        <span class="badge bg-secondary">COD (Tiền mặt)</span>
                                    @elseif($order->payment->payment_method === 'momo')
                                        <span class="badge bg-danger">MoMo</span>
                                    @else
                                        <span
                                            class="badge bg-dark">{{ strtoupper($order->payment->payment_method) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Trạng thái</div>
                                <div>
                                    @if ($order->payment->status === 'completed')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill"></i> Đã thanh toán
                                        </span>
                                    @elseif($order->payment->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock-fill"></i> Chưa thanh toán
                                        </span>
                                    @elseif($order->payment->status === 'failed')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill"></i> Thanh toán thất bại
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->payment->status }}</span>
                                    @endif
                                </div>
                            </div>
                            @if ($order->payment->transaction_id)
                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Mã giao dịch</div>
                                    <div class="font-monospace small">{{ $order->payment->transaction_id }}</div>
                                </div>
                            @endif
                            @if ($order->payment->payment_date)
                                <div class="mb-0">
                                    <div class="text-muted small mb-1">Ngày thanh toán</div>
                                    <div>{{ \Carbon\Carbon::parse($order->payment->payment_date)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <p class="text-muted mb-0">Chưa có thông tin thanh toán</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
