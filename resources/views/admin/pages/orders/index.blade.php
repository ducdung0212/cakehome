@extends('admin.layouts.master')

@section('title', 'Quản Lý Đơn Hàng')

@section('breadcrumb')
    <li class="breadcrumb-item active">Đơn Hàng</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Quản Lý Đơn Hàng</h1>
            </div>
        </div>

        <!-- Order Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                        <p class="mb-0">Chờ Xác Nhận</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $stats['processing'] }}</h3>
                        <p class="mb-0">Đang Chuẩn Bị</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-info" style="border-color: #17a2b8 !important;">
                    <div class="card-body text-center">
                        <h3 style="color: #17a2b8;">{{ $stats['ready'] }}</h3>
                        <p class="mb-0">Đã Chuẩn Bị</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-secondary">
                    <div class="card-body text-center">
                        <h3 class="text-secondary">{{ $stats['shipping'] }}</h3>
                        <p class="mb-0">Đang Giao</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ $stats['completed'] }}</h3>
                        <p class="mb-0">Hoàn Thành</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <h3 class="text-danger">{{ $stats['cancelled'] }}</h3>
                        <p class="mb-0">Đã Hủy</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.orders.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Mã đơn, khách hàng..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                                </option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác
                                    nhận</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang
                                    chuẩn bị</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Đã chuẩn bị
                                </option>
                                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn
                                    thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="payment_status" class="form-select">
                                <option value="">Thanh toán</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chưa
                                    thanh toán</option>
                                <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>
                                    Đã thanh toán</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất
                                    bại</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Tìm
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="ordersTable">
                        <thead>
                            <tr>
                                <th width="6%">Mã Đơn</th>
                                <th width="15%">Khách Hàng</th>
                                <th width="12%">Tổng Tiền</th>
                                <th width="12%">Thanh Toán</th>
                                <th width="12%">Trạng Thái</th>
                                <th width="12%">Ngày Đặt</th>
                                <th width="12%">Giao Hàng</th>
                                <th width="12%">Hẹn giao</th>
                                <th width="7%" class="text-end">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="text-primary fw-bold">#{{ $order->id }}</a></td>
                                    <td>
                                        <div class="fw-bold">{{ $order->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->user->phone_number ?? 'N/A' }}</small>
                                    </td>
                                    <td class="fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                    <td>
                                        @if ($order->payment)
                                            @if ($order->payment->payment_method == 'momo')
                                                <span class="badge bg-danger">MoMo</span>
                                            @elseif($order->payment->payment_method == 'cash')
                                                <span class="badge bg-warning">COD</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary">{{ strtoupper($order->payment->payment_method) }}</span>
                                            @endif
                                            <div>
                                                @if ($order->payment->status == 'completed')
                                                    <small class="text-success">Đã thanh toán</small>
                                                @elseif($order->payment->status == 'pending')
                                                    <small class="text-warning">Chưa thanh toán</small>
                                                @else
                                                    <small class="text-danger">Thất bại</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->status == 'pending')
                                            <span class="badge bg-warning">Chờ xác nhận</span>
                                        @elseif($order->status == 'confirmed')
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-primary">Đang chuẩn bị</span>
                                        @elseif($order->status == 'ready')
                                            <span class="badge bg-info">Đã chuẩn bị</span>
                                        @elseif($order->status == 'shipping')
                                            <span class="badge bg-secondary">Đang giao</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-success">Đã giao</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @else
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('H:i A') }}</small>
                                    </td>
                                    <td>
                                        @if ($order->delivery_method == 'delivery')
                                            <div>Giao tận nơi</div>
                                        @else
                                            <div>Nhận tại shop</div>
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->delivery_at)
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($order->delivery_at)->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Chưa có đơn hàng nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation" class="mt-5">
                        {{ $orders->appends(request()->query())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
