@extends('client.layouts.master')

@section('title', 'Tài Khoản - CakeHome')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle bg-primary text-white mb-3">
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-house-door me-2"></i> Tổng Quan
                        </a>
                        <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-person me-2"></i> Thông Tin Cá Nhân
                        </a>
                        <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-geo-alt me-2"></i> Địa Chỉ
                        </a>
                        <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-bag me-2"></i> Đơn Hàng
                        </a>
                        <a href="{{ route('account.change-password') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-key me-2"></i> Đổi Mật Khẩu
                        </a>
                        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng Xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h3 class="mb-4">Tổng Quan Tài Khoản</h3>
            
            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="bi bi-person fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Thông Tin Cá Nhân</h5>
                                    <small class="text-muted">Quản lý thông tin của bạn</small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <strong>Họ tên:</strong> {{ $user->name }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $user->email }}
                            </div>
                            <div class="mb-3">
                                <strong>Số điện thoại:</strong> {{ $user->phone_number ?? 'Chưa cập nhật' }}
                            </div>
                            <a href="{{ route('account.profile') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> Chỉnh Sửa
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-bag fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Đơn Hàng</h5>
                                    <small class="text-muted">Theo dõi đơn hàng của bạn</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h2 class="mb-0">{{ $user->orders->count() }}</h2>
                                <small class="text-muted">Tổng số đơn hàng</small>
                            </div>
                            <a href="{{ route('account.orders') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-eye me-1"></i> Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Addresses Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                    <i class="bi bi-geo-alt fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Địa Chỉ</h5>
                                    <small class="text-muted">Quản lý địa chỉ giao hàng</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h2 class="mb-0">{{ $user->shippingAddresses->count() }}</h2>
                                <small class="text-muted">Địa chỉ đã lưu</small>
                            </div>
                            <a href="{{ route('account.addresses') }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Quản Lý Địa Chỉ
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="bi bi-shield-lock fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Bảo Mật</h5>
                                    <small class="text-muted">Thay đổi mật khẩu</small>
                                </div>
                            </div>
                            <p class="mb-3">Đảm bảo tài khoản của bạn an toàn bằng cách sử dụng mật khẩu mạnh.</p>
                            <a href="{{ route('account.change-password') }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-key me-1"></i> Đổi Mật Khẩu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.icon-box {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.list-group-item-action.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
</style>
@endsection
