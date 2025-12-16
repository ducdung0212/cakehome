@extends('admin.layouts.master')

@section('title', 'Chi Tiết Khách Hàng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Khách Hàng</a></li>
    <li class="breadcrumb-item active">Chi Tiết</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="page-title">Chi Tiết Khách Hàng</h1>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay Lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Info Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Thông Tin Khách Hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="35%" class="text-muted"><strong>ID:</strong></td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Họ Tên:</strong></td>
                                <td class="fw-bold">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Điện Thoại:</strong></td>
                                <td>{{ $user->phone_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="35%" class="text-muted"><strong>Trạng Thái:</strong></td>
                                <td>
                                    @if ($user->status === 'active')
                                        <span class="badge bg-success">{{ $user->status_text }}</span>
                                    @elseif($user->status === 'pending')
                                        <span class="badge bg-warning">{{ $user->status_text }}</span>
                                    @elseif($user->status === 'banned')
                                        <span class="badge bg-danger">{{ $user->status_text }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->status_text }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Ngày Đăng Ký:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Cập Nhật:</strong></td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Tổng Đơn Hàng:</strong></td>
                                <td><span class="badge bg-info">{{ $user->orders->count() }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cart-check me-2"></i>Đơn Hàng ({{ $user->orders->count() }})</h5>
            </div>
            <div class="card-body">
                @if ($user->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Mã Đơn</th>
                                    <th width="15%">Ngày Đặt</th>
                                    <th width="15%">Tổng Tiền</th>
                                    <th width="15%">Trạng Thái</th>
                                    <th width="15%">Thanh Toán</th>
                                    <th width="20%">Địa Chỉ Giao</th>
                                    <th width="10%" class="text-end">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="fw-bold text-success">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                        </td>
                                        <td>
                                            @if ($order->status === 'completed')
                                                <span class="badge bg-success">{{ $order->status_text }}</span>
                                            @elseif($order->status === 'shipping')
                                                <span class="badge bg-info">{{ $order->status_text }}</span>
                                            @elseif($order->status === 'pending')
                                                <span class="badge bg-warning">{{ $order->status_text }}</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge bg-danger">{{ $order->status_text }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->status_text }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->payment)
                                                <span
                                                    class="badge bg-{{ $order->payment->status === 'paid' ? 'success' : 'warning' }}">
                                                    {{ $order->payment->payment_method }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->shippingAddress)
                                                <small>{{ Str::limit($order->shippingAddress->address, 30) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary" title="Chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">Chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Shipping Addresses Section -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Sổ Địa Chỉ ({{ $user->shippingAddresses->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if ($user->shippingAddresses->count() > 0)
                    <div class="row">
                        @foreach ($user->shippingAddresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 {{ $address->default ? 'border-primary' : '' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">
                                                <i class="bi bi-person me-1"></i>{{ $address->full_name }}
                                            </h6>
                                            @if ($address->default)
                                                <span class="badge bg-primary">Mặc định</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <i class="bi bi-telephone me-1"></i>
                                            <span>{{ $address->phone_number }}</span>
                                        </div>
                                        <div class="text-muted">
                                            <p class="mb-3">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ $address->address }}
                                                @if ($address->ward)
                                                    , {{ $address->ward }}
                                                @endif
                                                @if ($address->district)
                                                    , {{ $address->district }}
                                                @endif
                                                @if ($address->province)
                                                    , {{ $address->province }}
                                                @endif
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-house-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">Chưa có địa chỉ nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
