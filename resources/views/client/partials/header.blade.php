<!-- Top Bar -->
<div class="bg-dark text-white py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small>
                    <i class="bi bi-telephone"></i> Hotline: 1900-xxxx
                    <span class="ms-3"><i class="bi bi-envelope"></i> info@cakehome.vn</span>
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small>
                    <a href="#" class="text-white text-decoration-none me-3">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-white text-decoration-none me-3">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <span class="ms-3">Miễn phí ship đơn > 500k</span>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logo/favicon.png') }}" alt="CakeHome Logo" style="height: 35px;"> CakeHome
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Trang Chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Sản Phẩm
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/products">Tất Cả Sản Phẩm</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/products?category=cake">Bánh Kem</a></li>
                        <li><a class="dropdown-item" href="/products?category=cookies">Cookies</a></li>
                        <li><a class="dropdown-item" href="/products?category=macaron">Macaron</a></li>
                        <li><a class="dropdown-item" href="/products?category=bread">Bánh Mì Ngọt</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('about*') ? 'active' : '' }}" href="/about">Về Chúng Tôi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('contact*') ? 'active' : '' }}" href="/contact">Liên Hệ</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center">
                <a href="/search" class="nav-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search fs-5"></i>
                </a>
                <a href="/wishlist" class="nav-link position-relative mx-3">
                    <i class="bi bi-heart fs-5"></i>
                    <span class="cart-badge">0</span>
                </a>
                <a href="/cart" class="nav-link position-relative mx-3">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span class="cart-badge">0</span>
                </a>
                
                @guest
                    <a href="/login" class="btn btn-primary-custom">
                        <i class="bi bi-person"></i> Đăng Nhập
                    </a>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person"></i> Tài Khoản</a></li>
                            <li><a class="dropdown-item" href="/orders"><i class="bi bi-bag"></i> Đơn Hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                                        <i class="bi bi-box-arrow-right"></i> Đăng Xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Tìm Kiếm Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/search" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control" placeholder="Nhập tên sản phẩm...">
                        <button class="btn btn-primary-custom" type="submit">
                            <i class="bi bi-search"></i> Tìm
                        </button>
                    </div>
                </form>
                <div class="mt-3">
                    <small class="text-muted">Từ khóa phổ biến:</small>
                    <div class="mt-2">
                        <a href="#" class="badge bg-light text-dark me-2">Bánh sinh nhật</a>
                        <a href="#" class="badge bg-light text-dark me-2">Chocolate</a>
                        <a href="#" class="badge bg-light text-dark me-2">Macaron</a>
                        <a href="#" class="badge bg-light text-dark me-2">Tiramisu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
