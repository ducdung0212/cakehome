@extends('client.layouts.master')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->id . ' - CakeHome')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            @include('client.account.partials.sidebar')

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-receipt text-primary me-2"></i>
                        Chi Tiết Đơn Hàng #{{ $order->id }}
                    </h4>
                    <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>

                <!-- Order Status Timeline -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Trạng Thái Đơn Hàng
                        </h6>
                        <div class="timeline">
                            @forelse($order->orderStatusHistories as $index => $history)
                                <div class="timeline-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="timeline-marker">
                                        @if ($history->status === 'pending')
                                            <i class="bi bi-clock-fill text-warning"></i>
                                        @elseif($history->status === 'confirmed')
                                            <i class="bi bi-check-circle-fill text-info"></i>
                                        @elseif($history->status === 'processing')
                                            <i class="bi bi-gear-fill text-primary"></i>
                                        @elseif($history->status === 'ready')
                                            <i class="bi bi-check-circle-fill text-info"></i>
                                        @elseif($history->status === 'shipping')
                                            <i class="bi bi-truck text-primary"></i>
                                        @elseif($history->status === 'delivered')
                                            <i class="bi bi-box-seam text-success"></i>
                                        @elseif($history->status === 'completed')
                                            <i class="bi bi-check-all text-success"></i>
                                        @else
                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <p class="mb-0 fw-bold">
                                            @if ($history->status === 'pending')
                                                Đơn hàng đang chờ xác nhận
                                            @elseif($history->status === 'confirmed')
                                                Đã xác nhận đơn hàng
                                            @elseif($history->status === 'processing')
                                                Đang chuẩn bị hàng
                                            @elseif($history->status === 'ready')
                                                Đã chuẩn bị hàng
                                            @elseif($history->status === 'shipping')
                                                Đang giao hàng
                                            @elseif($history->status === 'delivered')
                                                Đã giao hàng
                                            @elseif($history->status === 'completed')
                                                Hoàn thành
                                            @else
                                                Đã hủy
                                            @endif
                                        </p>
                                        <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                        @if ($history->notes)
                                            <p class="mb-0 mt-1 text-muted small">{{ $history->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">Chưa có lịch sử cập nhật</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-info-circle text-primary me-2"></i>
                                    Thông Tin Đơn Hàng
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" style="width: 40%;">Mã đơn hàng:</td>
                                        <td class="fw-bold">#{{ $order->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Ngày đặt:</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Hẹn giao lúc:</td>
                                        <td class="text-primary">
                                            <i class="bi bi-clock"></i>
                                            @if ($order->delivery_at)
                                                {{ $order->delivery_at->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Phương thức:</td>
                                        <td>
                                            @if ($order->delivery_method === 'delivery')
                                                <span class="badge bg-info">Giao hàng tận nơi</span>
                                            @else
                                                <span class="badge bg-secondary">Lấy tại cửa hàng</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Thanh toán:</td>
                                        <td>
                                            @if ($order->payment)
                                                @if ($order->payment->payment_method === 'cash')
                                                    <span class="badge bg-secondary">COD</span>
                                                @elseif($order->payment->payment_method === 'momo')
                                                    <span class="badge bg-danger">MoMo</span>
                                                @else
                                                    <span
                                                        class="badge bg-primary">{{ strtoupper($order->payment->payment_method) }}</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    Trạng thái:
                                                    @if ($order->payment->status === 'completed')
                                                        <span class="text-success fw-bold">
                                                            <i class="bi bi-check-circle-fill"></i> Đã thanh toán
                                                        </span>
                                                    @elseif($order->payment->status === 'pending')
                                                        <span class="text-warning fw-bold">
                                                            <i class="bi bi-clock-fill"></i> Chưa thanh toán
                                                        </span>
                                                    @elseif($order->payment->status === 'failed')
                                                        <span class="text-danger fw-bold">
                                                            <i class="bi bi-x-circle-fill"></i> Thanh toán thất bại
                                                        </span>
                                                    @else
                                                        <span class="text-secondary">{{ $order->payment->status }}</span>
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">Chưa có thông tin</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    Địa Chỉ Giao Hàng
                                </h6>
                                @if ($order->shippingAddress)
                                    <p class="mb-1 fw-bold">{{ $order->shippingAddress->full_name }}</p>
                                    <p class="mb-1">
                                        <i class="bi bi-telephone"></i> {{ $order->shippingAddress->phone_number }}
                                    </p>
                                    <p class="mb-0 text-muted">
                                        {{ $order->shippingAddress->address }},
                                        {{ $order->shippingAddress->ward }},
                                        {{ $order->shippingAddress->district }},
                                        {{ $order->shippingAddress->province }}
                                    </p>
                                @else
                                    <p class="text-muted mb-0">Chưa có địa chỉ giao hàng</p>
                                @endif

                                @if ($order->notes)
                                    <hr>
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-chat-left-text text-primary me-2"></i>
                                        Ghi Chú
                                    </h6>
                                    <p class="mb-0 text-muted">{{ $order->notes }}</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-cart text-primary me-2"></i>
                        Sản Phẩm Đã Đặt
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 45%;">Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                    @if ($order->status === 'completed')
                                        <th class="text-center">Đánh giá</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product && $item->product->images->first())
                                                    <img src="{{ $item->product->firstImage ? asset('storage/' . $item->product->firstImage->image) : asset('images/no-image-product.png') }}"
                                                        alt="{{ $item->product_name }}" class="img-thumbnail me-3"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="mb-0 fw-bold">{{ $item->product->name }}</p>
                                                    @if ($item->product)
                                                        <small
                                                            class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ number_format($item->price) }}đ
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-light text-dark">x{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end align-middle fw-bold">
                                            {{ number_format($item->price * $item->quantity) }}vND
                                        </td>
                                        <td class="text-center align-middle">
                                            @if ($order->status === 'completed' && $item->product)
                                                @if (!in_array($item->product_id, $reviewedProductIds))
                                                    <a href="{{ route('product.detail', $item->product->slug) }}#reviews"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Đánh giá sản phẩm này">
                                                        <i class="bi bi-star"></i> Đánh giá
                                                    </a>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Đã đánh giá
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-calculator text-primary me-2"></i>
                        Tổng Kết Đơn Hàng
                    </h6>
                    <div class="row">
                        <div class="col-md-6 ms-auto">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Tạm tính:</td>
                                    <td class="text-end">{{ number_format($order->subtotal_price) }}VND</td>
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
                                        {{ number_format($order->total_price) }}đ
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-3">
                <div class="d-flex gap-2 justify-content-end">
                    {{-- Thanh toán lại cho đơn MoMo chưa thanh toán --}}
                    @if (
                        $order->payment &&
                            $order->payment->payment_method === 'momo' &&
                            $order->payment->status !== 'completed' &&
                            in_array($order->status, ['pending', 'confirmed']))
                        <form action="{{ route('orders.retry-payment', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-credit-card"></i> Thanh Toán Ngay
                            </button>
                        </form>
                    @endif

                    {{-- Hủy đơn hàng --}}
                    @if (in_array($order->status, ['pending', 'confirmed']))
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Hủy Đơn Hàng
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Thông báo về hoàn tiền --}}
                @if ($order->payment && $order->payment->payment_method === 'momo' && $order->payment->status === 'completed')
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Lưu ý:</strong> Đơn hàng đã thanh toán qua MoMo không thể tự hủy.
                        Vui lòng liên hệ shop để được hỗ trợ hoàn tiền nếu cần thiết.
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 30px;
            width: 2px;
            height: calc(100% - 10px);
            background: #dee2e6;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-marker {
            position: absolute;
            left: -40px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: white;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-item.active .timeline-marker {
            border-color: var(--bs-primary);
            background: #e7f3ff;
        }

        .timeline-item.active .timeline-marker i {
            font-size: 14px;
        }
    </style>

    @push('scripts')
        <script>
            // Enable Bootstrap tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
@endsection
