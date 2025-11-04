@extends('client.layouts.master')

@section('title', 'CakeHome - Thế Giới Bánh Ngọt Cao Cấp')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background-image: url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1600');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">HƯƠNG VỊ PHÉP MÀU</h1>
        <p class="hero-subtitle">Khám phá bộ sưu tập bánh ngọt cao cấp</p>
        <a href="/products" class="btn btn-primary-custom btn-lg me-3">
            <i class="bi bi-shop"></i> Khám Phá Ngay
        </a>
        <a href="/about" class="btn btn-outline-light btn-lg">
            <i class="bi bi-info-circle"></i> Về Chúng Tôi
        </a>
    </div>
</section>

<!-- Announcement Bar -->
<div class="bg-warning text-dark text-center py-2">
    <div class="container">
        <i class="bi bi-gift"></i> <strong>Ưu đãi đặc biệt:</strong> Giảm 20% cho đơn hàng đầu tiên! Mã: <strong>WELCOME20</strong>
    </div>
</div>

<!-- Categories Section -->
<section class="py-5 bg-light-custom">
    <div class="container">
        <h2 class="section-title">Danh Mục Sản Phẩm</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="/products?category=cake" class="text-decoration-none">
                    <div class="category-box">
                        <img src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=600" alt="Bánh Kem">
                        <div class="category-overlay">
                            <h3 class="category-name">Bánh Kem</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/products?category=cookies" class="text-decoration-none">
                    <div class="category-box">
                        <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=600" alt="Cookies">
                        <div class="category-overlay">
                            <h3 class="category-name">Cookies & Bánh Quy</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/products?category=macaron" class="text-decoration-none">
                    <div class="category-box">
                        <img src="https://images.unsplash.com/photo-1587668178277-295251f900ce?w=600" alt="Macaron">
                        <div class="category-overlay">
                            <h3 class="category-name">Macaron</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Sản Phẩm Nổi Bật</h2>
        <div class="row g-4">
            @for($i = 1; $i <= 8; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="product-card card h-100">
                    @if($i % 3 == 0)
                    <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px; z-index: 10;">Sale 20%</span>
                    @endif
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=400&fit=crop" 
                         class="product-image" alt="Product {{ $i }}">
                    <div class="card-body">
                        <h5 class="product-title">Bánh Chocolate Deluxe</h5>
                        <div class="product-rating mb-2">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                            <span class="text-muted">(128)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($i % 3 == 0)
                                <span class="product-price">360.000 đ</span>
                                <small class="text-muted text-decoration-line-through d-block">450.000 đ</small>
                                @else
                                <span class="product-price">450.000 đ</span>
                                @endif
                            </div>
                            <button class="btn btn-primary-custom btn-sm" onclick="addToCart({{ $i }})">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="/products/{{ $i }}" class="btn btn-outline-custom w-100 btn-sm">
                            <i class="bi bi-eye"></i> Chi Tiết
                        </a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-5">
            <a href="/products" class="btn btn-outline-custom btn-lg">
                <i class="bi bi-grid"></i> Xem Tất Cả Sản Phẩm
            </a>
        </div>
    </div>
</section>

<!-- Banner Promotion -->
<section class="py-5" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                <h2 class="display-5 fw-bold">Đặt Bánh Sinh Nhật</h2>
                <p class="lead">Tùy chỉnh theo ý bạn - Giao hàng tận nơi</p>
                <a href="/custom-order" class="btn btn-light btn-lg">
                    <i class="bi bi-cake2"></i> Đặt Ngay
                </a>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://images.unsplash.com/photo-1535254973040-607b474cb50d?w=500" 
                     alt="Birthday Cake" class="img-fluid rounded-circle" style="max-width: 300px; border: 10px solid rgba(255,255,255,0.3);">
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5 bg-light-custom">
    <div class="container">
        <h2 class="section-title">Tại Sao Chọn CakeHome?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-3 col-sm-6">
                <div class="p-4">
                    <i class="bi bi-award text-primary-custom" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Chất Lượng Cao Cấp</h5>
                    <p class="text-muted">Nguyên liệu nhập khẩu chọn lọc</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4">
                    <i class="bi bi-truck text-primary-custom" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Giao Hàng Nhanh</h5>
                    <p class="text-muted">Miễn phí ship đơn > 500k</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4">
                    <i class="bi bi-heart text-primary-custom" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Làm Thủ Công</h5>
                    <p class="text-muted">Mỗi chiếc bánh đều đặc biệt</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4">
                    <i class="bi bi-shield-check text-primary-custom" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">An Toàn Vệ Sinh</h5>
                    <p class="text-muted">Đảm bảo tiêu chuẩn ATTP</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Khách Hàng Nói Gì</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted">"Bánh rất ngon, mềm và không quá ngọt. Giao hàng nhanh, đóng gói cẩn thận. Sẽ ủng hộ tiếp!"</p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="https://i.pravatar.cc/50?img=1" class="rounded-circle me-3" alt="Avatar">
                        <div>
                            <h6 class="mb-0">Nguyễn Thị Mai</h6>
                            <small class="text-muted">Khách hàng thân thiết</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted">"Đặt bánh sinh nhật cho công ty, mọi người đều khen ngon. Thiết kế đẹp, giá hợp lý."</p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="https://i.pravatar.cc/50?img=2" class="rounded-circle me-3" alt="Avatar">
                        <div>
                            <h6 class="mb-0">Trần Văn Hoàng</h6>
                            <small class="text-muted">Khách hàng doanh nghiệp</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="text-muted">"Macaron ở đây chính hiệu, ngon như ở Pháp. Mình đã thử nhiều nơi nhưng CakeHome ngon nhất!"</p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="https://i.pravatar.cc/50?img=3" class="rounded-circle me-3" alt="Avatar">
                        <div>
                            <h6 class="mb-0">Lê Thu Hà</h6>
                            <small class="text-muted">Food Blogger</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3><i class="bi bi-envelope-heart"></i> Đăng Ký Nhận Tin</h3>
                <p>Nhận thông tin khuyến mãi, sản phẩm mới và các ưu đãi đặc biệt!</p>
            </div>
            <div class="col-md-6">
                <form action="/newsletter/subscribe" method="POST">
                    @csrf
                    <div class="input-group input-group-lg">
                        <input type="email" name="email" class="form-control" placeholder="Email của bạn" required>
                        <button class="btn btn-primary-custom" type="submit">
                            <i class="bi bi-send"></i> Đăng Ký
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
