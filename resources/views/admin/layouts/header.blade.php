<header class="admin-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <button class="btn btn-link sidebar-toggle-btn d-lg-none" id="sidebarToggleBtn">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <button class="btn btn-link sidebar-collapse-btn d-none d-lg-block" id="sidebarCollapseBtn">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>

            <div class="col-auto ms-auto">
                <ul class="header-nav">
                    <!-- Search -->
                    <li class="nav-item d-none d-md-block">
                        <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <i class="bi bi-search fs-5"></i>
                        </button>
                    </li>

                    <!-- Notifications -->
                    @include('admin.components.notifications-dropdown')

                    <!-- Quick Actions -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-plus-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/admin/products/create">
                                    <i class="bi bi-box-seam me-2"></i>Thêm Sản Phẩm
                                </a></li>
                            <li><a class="dropdown-item" href="/admin/categories/create">
                                    <i class="bi bi-tags me-2"></i>Thêm Danh Mục
                                </a></li>
                            <li><a class="dropdown-item" href="/admin/vouchers/create">
                                    <i class="bi bi-ticket-perforated me-2"></i>Thêm Voucher
                                </a></li>
                        </ul>
                    </li>

                    <!-- User Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin"
                                class="user-avatar me-2">
                            <span class="d-none d-md-inline">{{ $user->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">
                                <strong>{{ $user->email }}</strong>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/admin/profile">
                                    <i class="bi bi-person me-2"></i>Hồ Sơ
                                </a></li>
                            <li><a class="dropdown-item" href="/admin/profile">
                                    <i class="bi bi-shield-lock me-2"></i>Đổi Mật Khẩu
                                </a></li>
                            <li><a class="dropdown-item" href="/admin/settings/general">
                                    <i class="bi bi-gear me-2"></i>Cài Đặt
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/">
                                    <i class="bi bi-house me-2"></i>Xem Website
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng Xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
