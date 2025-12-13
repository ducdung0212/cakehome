@extends('client.layouts.master')

@section('title', 'Chi Tiết Sản Phẩm - CakeHome')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/client/css/product-detail.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/client/js/product-detail.js') }}"></script>
    <script src="{{ asset('assets/client/js/carousel-helper.js') }}"></script>
    <script src="{{ asset('assets/client/js/cart.js') }}"></script>
@endpush

@section('content')


    <!-- Product Detail -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <img src="{{ $product->firstImage ? asset('storage/Product/' . $product->firstImage->image) : asset('images/no-image-product.png') }}"
                        class="product-image-main w-100 mb-3" id="mainImage" alt="{{ $product->name }}">
                    <div class="row g-2">
                        @foreach ($product->images as $image)
                            <div class="col-3">
                                <img src="{{ $image->image ? asset('storage/Product/' . $image->image) : asset('images/no-image-product.png') }}"
                                    class="product-thumbnail w-100 active" onclick="changeImage(this)">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    @if ($product->status == 'in_stock')
                        <span class="badge bg-success mb-2">
                            Còn hàng: {{ $product->stock }}
                        </span>
                    @else
                        <span class="bg-warning text-dark">Hết hàng</span>
                    @endif
                    <h1 class="mb-3">{{ $product->name }}</h1>

                    <div class="mb-3">
                        <span class="fs-4 text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </span>
                        <span class="text-muted ms-2">(4.5 sao - 128 đánh giá)</span>
                    </div>

                    <div class="mb-4">
                        @if ($product->sale_price)
                            @php
                                $discountPercent = round(
                                    (($product->price - $product->sale_price) / $product->price) * 100,
                                );
                            @endphp
                            <h2 class="text-primary-custom d-inline">
                                {{ number_format($product->sale_price, 0, ',', '.') }}VNĐ</h2>
                            <span
                                class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }}VNĐ</span>
                            <span class="badge bg-danger ms-2">{{ $discountPercent }}%</span>
                        @else
                            <h2 class="text-primary-custom d-inline">{{ number_format($product->price, 0, ',', '.') }}VNĐ
                            </h2>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>Số Lượng:</h5>
                        <div class="input-group" style="width: 150px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty()">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control quantity-input" value="1" min="1"
                                id="quantity">
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseQty()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        @include('client.partials.addToCart-button', [
                            'productId' => $product->id,
                            'stock' => $product->stock,
                            'class' => 'ms-2',
                        ])
                        <div class="position-relative">
                            @include('client.partials.wishlist-button', [
                                'productId' => $product->id,
                                'active' => in_array($product->id, $wishlistProductIds),
                            ])
                        </div>
                        <button class="btn btn-outline-custom btn-lg">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <i class="bi bi-truck text-primary-custom"></i>
                                <strong> Giao hàng nhanh</strong>
                                <br><small class="text-muted">Giao trong 2-4 giờ</small>
                            </div>
                            <div class="col-md-6">
                                <i class="bi bi-shield-check text-primary-custom"></i>
                                <strong> Đảm bảo chất lượng</strong>
                                <br><small class="text-muted">Hoàn tiền 100%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="row mt-5">
                <div class="col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#description">Mô Tả Chi Tiết</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#reviews">Đánh Giá (128)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#shipping">Vận Chuyển</a>
                        </li>
                    </ul>

                    <div class="tab-content p-4 border border-top-0">
                        <div id="description"class="tab-pane fade show active">
                            <h5>Mô Tả:</h5>
                            <p class="text-muted">
                                {{ $product->description }}
                            </p>
                        </div>
                        <div id="reviews" class="tab-pane fade">
                            <h4 class="mb-4">Đánh Giá Khách Hàng</h4>

                            @for ($i = 1; $i <= 3; $i++)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <img src="https://i.pravatar.cc/50?img={{ $i }}"
                                                    class="rounded-circle me-3" style="width: 50px; height: 50px;">
                                                <div>
                                                    <h6 class="mb-1">Khách hàng {{ $i }}</h6>
                                                    <div class="text-warning mb-2">
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $i }} ngày trước</small>
                                        </div>
                                        <p class="mb-0">Bánh rất ngon, kem mềm và chocolate đậm đà. Giao hàng đúng giờ và
                                            đóng gói cẩn thận!</p>
                                    </div>
                                </div>
                            @endfor

                            <button class="btn btn-outline-custom">Xem Thêm Đánh Giá</button>
                        </div>

                        <div id="shipping" class="tab-pane fade">
                            <h4>Chính Sách Vận Chuyển</h4>
                            <ul>
                                <li>Giao hàng nội thành: 2-4 giờ</li>
                                <li>Giao hàng ngoại thành: 1-2 ngày</li>
                                <li>Miễn phí ship cho đơn hàng trên 500.000đ</li>
                                <li>Đóng gói cẩn thận, đảm bảo sản phẩm nguyên vẹn</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <section class="py-5">
                <div class="container">
                    <div class="col-12 mb-3">
                        <h3 class="section-title">Sản Phẩm Liên Quan</h3>
                    </div>

                    <div class="position-relative">
                        <button class="carousel-control-custom carousel-control-prev" id="relatedPrev">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <div class="carousel-container-wrapper">
                            <div class="d-flex" id="relatedProductCarousel"
                                style="transition: transform 0.3s ease-out; gap: 20px;">
                                @foreach ($relatedProducts as $product)
                                    <div class="product-carousel-item" style="flex: 0 0 280px; max-width: 280px;">
                                        <div class="product-card card h-100">
                                            <img src="{{ $product->firstImage ? asset('storage/Product/' . $product->firstImage->image) : asset('images/no-image-product.png') }}"
                                                class="product-image" alt="{{ $product->name }}"
                                                style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="product-title text-truncate">{{ $product->name }}</h5>
                                                <div class="product-rating mb-2">
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                    <i class="bi bi-star-half text-warning"></i>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span
                                                        class="product-price fw-bold">{{ number_format($product->price, 0, ',', '.') }}VNĐ</span>
                                                    <button class="btn btn-primary-custom btn-sm">
                                                        <i class="bi bi-cart-plus"></i>
                                                    </button>
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
                        </div>

                        <button class="carousel-control-custom carousel-control-next" id="relatedNext">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
