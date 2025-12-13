<div class="col-md-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="avatar-circle bg-primary text-white mb-3">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                <small class="text-muted">{{ Auth::user()->email }}</small>
            </div>
            
            <div class="list-group list-group-flush">
                <a href="{{ route('account.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('account.index') ? 'active' : '' }}">
                    <i class="bi bi-house-door me-2"></i> Tổng Quan
                </a>
                <a href="{{ route('account.profile') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                    <i class="bi bi-person me-2"></i> Thông Tin Cá Nhân
                </a>
                <a href="{{ route('account.addresses') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('account.addresses') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt me-2"></i> Địa Chỉ
                </a>
                <a href="{{ route('account.orders') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('account.orders') ? 'active' : '' }}">
                    <i class="bi bi-bag me-2"></i> Đơn Hàng
                </a>
                <a href="{{ route('account.change-password') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('account.change-password') ? 'active' : '' }}">
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

.list-group-item-action.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.list-group-item-action:hover:not(.active) {
    background-color: #f8f9fa;
}
</style>
