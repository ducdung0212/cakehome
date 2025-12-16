<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="/admin/dashboard" class="sidebar-brand">
            <img src="{{ asset('images/logo/favicon.png') }}" alt="Logo" class="sidebar-logo">
            <span class="brand-text">CakeHome Admin</span>
        </a>
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="sidebar-body">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin') || Request::is('admin/dashboard') ? 'active' : '' }}"
                        href="/admin/dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @php
                    $user = Auth::guard('admin')->user();
                    $userRole = $user->role->name;
                @endphp

                @if ($userRole == 'admin')
                    <!-- Products Management -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}" href="#productsSubmenu"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('admin/products*') ? 'true' : 'false' }}">
                            <i class="bi bi-box-seam"></i>
                            <span>Sản Phẩm</span>
                            <i class="bi bi-chevron-down ms-auto submenu-icon"></i>
                        </a>
                        <ul class="collapse submenu {{ Request::is('admin/products*') ? 'show' : '' }}"
                            id="productsSubmenu">
                            <li><a href="/admin/products"
                                    class="{{ Request::is('admin/products') && !Request::is('admin/products/create') ? 'active' : '' }}">Danh
                                    Sách</a></li>
                            <li><a href="/admin/products/create"
                                    class="{{ Request::is('admin/products/create') ? 'active' : '' }}">Thêm Mới</a></li>
                        </ul>
                    </li>
                @endif

                @if ($userRole == 'admin')
                    <!-- Categories -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}"
                            href="/admin/categories">
                            <i class="bi bi-tags"></i>
                            <span>Danh Mục</span>
                        </a>
                    </li>
                @endif

                <!-- Orders -->
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-cart-check"></i>
                        <span>Đơn Hàng</span>
                    </a>
                </li>

                <!-- Users -->
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}"
                        href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Khách Hàng</span>
                    </a>
                </li>
                @if ($userRole == 'admin')
                    <!-- Vouchers -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/vouchers*') ? 'active' : '' }}"
                            href="#vouchersSubmenu" data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('admin/vouchers*') ? 'true' : 'false' }}">
                            <i class="bi bi-ticket-perforated"></i>
                            <span>Voucher</span>
                            <i class="bi bi-chevron-down ms-auto submenu-icon"></i>
                        </a>
                        <ul class="collapse submenu {{ Request::is('admin/vouchers*') ? 'show' : '' }}"
                            id="vouchersSubmenu">
                            <li><a href="/admin/vouchers"
                                    class="{{ Request::is('admin/vouchers') && !Request::is('admin/vouchers/create') ? 'active' : '' }}">Danh
                                    Sách</a></li>
                            <li><a href="/admin/vouchers/create"
                                    class="{{ Request::is('admin/vouchers/create') ? 'active' : '' }}">Thêm Mới</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Reviews -->
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/reviews*') ? 'active' : '' }}"
                        href="{{ route('admin.reviews.index') }}">
                        <i class="bi bi-star"></i>
                        <span>Đánh Giá</span>
                    </a>
                </li>

                <li class="nav-divider"></li>
                @if ($userRole == 'admin')
                    <!-- Settings -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}"
                            href="#settingsSubmenu" data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is('admin/settings*') ? 'true' : 'false' }}">
                            <i class="bi bi-gear"></i>
                            <span>Cài Đặt</span>
                            <i class="bi bi-chevron-down ms-auto submenu-icon"></i>
                        </a>
                        <ul class="collapse submenu {{ Request::is('admin/settings*') ? 'show' : '' }}"
                            id="settingsSubmenu">
                            <li><a href="/admin/settings/general"
                                    class="{{ Request::is('admin/settings/general') ? 'active' : '' }}">Tổng Quan</a>
                            </li>
                            <li><a href="/admin/settings/shipping"
                                    class="{{ Request::is('admin/settings/shipping') ? 'active' : '' }}">Vận
                                    Chuyển</a></li>
                        </ul>
                    </li>
                @endif

                @if ($userRole == 'admin')
                    <!-- Reports -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" href="/admin/reports">
                            <i class="bi bi-bar-chart"></i>
                            <span>Báo Cáo</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin"
                class="user-avatar">
            <div class="user-details">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-role">{{ $userRole }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
