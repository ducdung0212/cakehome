@extends('client.layouts.master')

@section('title', 'CakeHome - Thế Giới Bánh Ngọt Cao Cấp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/client/css/home.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/client/js/home.js') }}"></script>
    <script src="{{ asset('assets/client/js/carousel-helper.js') }}"></script>
@endpush

@section('content')
    @php
        $heroBg =
            $siteSettings['home_hero_background_url'] ??
            'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1600';
        $heroTitle = $siteSettings['home_hero_title'] ?? 'HƯƠNG VỊ PHÉP MÀU';
        $heroSubtitle = $siteSettings['home_hero_subtitle'] ?? 'Khám phá bộ sưu tập bánh ngọt cao cấp';
        $announcement = $siteSettings['home_announcement_text'] ?? null;
    @endphp
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('{{ $heroBg }}');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">{{ $heroTitle }}</h1>
            <p class="hero-subtitle">{{ $heroSubtitle }}</p>
            <a href="/products" class="btn btn-primary-custom btn-lg me-3">
                <i class="bi bi-shop"></i> Khám Phá Ngay
            </a>
            <a href="/about" class="btn btn-outline-light btn-lg">
                <i class="bi bi-info-circle"></i> Về Chúng Tôi
            </a>
        </div>
    </section>

    <!-- Announcement Bar -->
    @if (!empty($announcement))
        <div class="bg-warning text-dark text-center py-2">
            <div class="container">
                <i class="bi bi-gift"></i> {!! e($announcement) !!}
            </div>
        </div>
    @endif

    <!-- Categories Section -->
    <section class="py-5 bg-light-custom">
        <div class="container">
            <h2 class="section-title">Danh Mục Sản Phẩm</h2>

            <div class="position-relative">
                <!-- Nút Previous -->
                <button class="carousel-control-custom carousel-control-prev" id="categoryPrev">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <!-- Container cuộn -->
                <div class="category-carousel-container">
                    <div class="category-carousel" id="categoryCarousel">
                        @foreach ($categories as $category)
                            <div class="category-item">
                                <a href="{{ route('products.index', ['categories' => [$category->id]]) }}"
                                    class="text-decoration-none">
                                    <div class="category-box">
                                        <img src="{{ asset('storage/' . $category->images) }}" alt="{{ $category->name }}">
                                        <div class="category-overlay">
                                            <h3 class="category-name">{{ $category->name }}</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nút Next -->
                <button class="carousel-control-custom carousel-control-next" id="categoryNext">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Sản Phẩm Bán Chạy</h2>
            <div class="row g-4">

                @foreach ($bestSellingProducts as $product)
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card card h-100 position-relative">

                            <!-- Wishlist button -->
                            <div class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                                @include('client.components.wishlist-button', [
                                    'productId' => $product->id,
                                    'active' => in_array($product->id, $wishlistProductIds),
                                ])
                            </div>

                            <img src="{{ $product->firstImage ? asset('storage/' . $product->firstImage->image) : asset('images/no-image-product.png') }}"
                                class="product-image" alt="{{ $product->name }} ">
                            <div class="card-body">
                                <h5 class="product-title">{{ $product->name }}</h5>
                                <div class="mb-2">
                                    <span class="text-warning">
                                        @php
                                            $avgRating = round($product->reviews_avg ?? 0, 1);
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($avgRating))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif ($i <= ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <small class="text-muted ms-1">
                                        ({{ $product->reviews_count ?? 0 }}) | Đã bán:
                                        {{ number_format($product->total_sold ?? 0) }}
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if ($product->sale_price)
                                            <span
                                                class="product-price text-danger fw-bold">{{ number_format($product->sale_price, 0, ',', '.') }}VNĐ</span>
                                            <small
                                                class="text-muted text-decoration-line-through d-block">{{ number_format($product->price, 0, ',', '.') }}
                                                đ</small>
                                        @else
                                            <span
                                                class="product-price">{{ number_format($product->price, 0, ',', '.') }}VNĐ</span>
                                        @endif
                                    </div>
                                    @include('client.components.addToCart-button', [
                                        'productId' => $product->id,
                                        'stock' => $product->stock, // Truyền số lượng tồn kho để check hết hàng
                                        'class' => 'ms-2', // Thêm class margin nếu cần
                                    ])
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <a href="{{ route('product.detail', $product->slug) }}"
                                    class="btn btn-outline-custom w-100 btn-sm">
                                    <i class="bi bi-eye"></i> Chi Tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
            <div class="text-center mt-5">
                <a href="{{ route('products.index') }}" class="btn btn-outline-custom btn-lg">
                    <i class="bi bi-grid"></i> Xem Tất Cả Sản Phẩm
                </a>
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
                        <p class="text-muted">"Bánh rất ngon, mềm và không quá ngọt. Giao hàng nhanh, đóng gói cẩn thận. Sẽ
                            ủng hộ tiếp!"</p>
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
                        <p class="text-muted">"Đặt bánh sinh nhật cho công ty, mọi người đều khen ngon. Thiết kế đẹp, giá
                            hợp lý."</p>
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
                        <p class="text-muted">"Macaron ở đây chính hiệu, ngon như ở Pháp. Mình đã thử nhiều nơi nhưng
                            CakeHome ngon nhất!"</p>
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
@endsection
