@extends('client.layouts.master')

@section('title', 'Đơn Hàng - CakeHome')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            @include('client.account.partials.sidebar')

            <!-- Main Content -->
            <div class="col-md-9">
                <h4 class="mb-4">
                    <i class="bi bi-bag text-primary me-2"></i>
                    Đơn Hàng Của Tôi
                </h4>

                @forelse($orders as $order)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <small class="text-muted">Mã đơn hàng</small>
                                    <p class="mb-1 fw-bold">#{{ $order->id }}</p>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Tổng tiền</small>
                                    <p class="mb-0 fw-bold text-danger">{{ number_format($order->total_price) }}VND</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Thời gian giao</small>
                                    <p class="mb-0 text-primary">
                                        <i class="bi bi-clock"></i>
                                        @if ($order->delivery_at)
                                            {{ $order->delivery_at->format('d/m/Y H:i') }}
                                        @else
                                            Chưa xác định
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Thanh toán</small>
                                    <p class="mb-0">
                                        @if ($order->payment)
                                            @if ($order->payment->payment_method === 'cash')
                                                <span class="badge bg-secondary">COD</span>
                                            @elseif($order->payment->payment_method === 'momo')
                                                <span class="badge bg-danger">MoMo</span>
                                            @else
                                                <span
                                                    class="badge bg-info">{{ strtoupper($order->payment->payment_method) }}</span>
                                            @endif
                                            <br>
                                            @if ($order->payment->status === 'completed')
                                                <small class="text-success"><i class="bi bi-check-circle"></i> Đã thanh
                                                    toán</small>
                                            @elseif($order->payment->status === 'failed')
                                                <small class="text-danger"><i class="bi bi-x-circle"></i> Thất bại</small>
                                            @else
                                                <small class="text-warning"><i class="bi bi-clock"></i> Chờ thanh
                                                    toán</small>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Trạng thái</small>
                                    <p class="mb-0">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xác nhận</span>
                                            @break

                                            @case('processing')
                                                <span class="badge bg-info">Đang xử lý</span>
                                            @break

                                            @case('shipping')
                                                <span class="badge bg-primary">Đang giao</span>
                                            @break

                                            @case('completed')
                                                <span class="badge bg-success">Hoàn thành</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                        @endswitch
                                    </p>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('account.orders.detail', $order->id) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i> Chi Tiết
                                    </a>
                                </div>
                            </div>

                            @if ($order->orderItems->count() > 0)
                                <hr class="my-3">
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">Sản phẩm:</small>
                                    @foreach ($order->orderItems->take(3) as $item)
                                        <span class="badge bg-light text-dark me-1">{{ $item->product_name }}</span>
                                    @endforeach
                                    @if ($order->orderItems->count() > 3)
                                        <small class="text-muted">+{{ $order->orderItems->count() - 3 }} sản phẩm
                                            khác</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">Chưa có đơn hàng nào</h5>
                            <p class="text-muted">Hãy khám phá và đặt hàng ngay!</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Tiếp Tục Mua Sắm
                            </a>
                        </div>
                    @endforelse

                    @if ($orders->hasPages())
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection
