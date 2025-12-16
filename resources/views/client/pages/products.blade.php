@extends('client.layouts.master')
@push('scripts')
    <script src="{{ asset('assets/client/js/cart.js') }}"></script>
@endpush

@section('title', 'Sản Phẩm - CakeHome')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-3">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Sản phẩm</li>
            </ol>
        </div>
    </nav>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('products.index') }}" method="GET" id="searchForm">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="q" class="form-control"
                                        value="{{ $searchQuery ?? '' }}"
                                        placeholder="Tìm kiếm sản phẩm theo tên, danh mục...">
                                    <button class="btn btn-primary-custom" type="submit">
                                        <i class="bi bi-search"></i> Tìm Kiếm
                                    </button>
                                    @if (!empty($searchQuery))
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-lg"></i> Xóa
                                        </a>
                                    @endif
                                </div>
                            </form>
                            @if (!empty($searchQuery))
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Kết quả tìm kiếm cho: <strong>"{{ $searchQuery }}"</strong>
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Filter -->

                <div class="col-lg-3 mb-4">
                    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-4"><i class="bi bi-funnel"></i> Bộ Lọc</h5>

                                <!-- Category Filter -->
                                <div class="mb-4">
                                    <h6 class="fw-bold">Danh Mục</h6>
                                    @foreach ($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $category->id }}"
                                                name="categories[]"
                                                {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Price Filter -->
                                <div class="mb-4">
                                    <h6 class="fw-bold">Khoảng Giá</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="price1"
                                            {{ request('price_range') == 'price1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price1">Dưới 100.000đ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="price2"
                                            {{ request('price_range') == 'price2' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price2">100.000đ - 300.000đ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="price3"
                                            {{ request('price_range') == 'price3' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price3">Trên 300.000đ</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary-custom w-100">
                                    <i class="bi bi-funnel"></i> Áp Dụng
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                    <i class="bi bi-x-circle"></i> Xóa Bộ Lọc
                                </a>
                            </div>
                        </div>

                        <!-- Special Offers -->
                        <div class="card border-0 shadow-sm mt-4 bg-warning">
                            <div class="card-body text-center">
                                <h5><i class="bi bi-gift"></i> Ưu Đãi Đặc Biệt</h5>
                                <p class="mb-0">Giảm 20% đơn đầu tiên</p>
                                <p class="mb-0"><strong>Mã: WELCOME20</strong></p>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Sort Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Tất Cả Sản Phẩm <span class="text-muted">({{ $products->total() }} sản
                                phẩm)</span></h4>
                        <div class="d-flex align-items-center">
                            <label class="me-2">Sắp xếp:</label>
                            <select class="form-select" style="width: auto;" id="sortSelect">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp
                                    đến cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao
                                    đến thấp</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-lg-4 col-md-6">
                                <div class="product-card card h-100">


                                    <div class="position-relative">
                                        <img src="{{ $product->firstImage ? asset('storage/' . $product->firstImage->image) : asset('images/no-image-product.png') }}"
                                            class="product-image" alt="{{ $product->name }}">
                                        <div class="position-absolute top-0 start-0 p-2">
                                            @include('client.components.wishlist-button', [
                                                'productId' => $product->id,
                                                'active' => in_array($product->id, $wishlistProductIds),
                                            ])
                                        </div>
                                    </div>

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
                                                ({{ $product->reviews_count ?? 0 }})
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if ($product->sale_price && $product->sale_price < $product->price)
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
                                                'stock' => $product->stock,
                                                'class' => 'ms-2',
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

                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-5">
                        {{ $products->appends(request()->query())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Sắp xếp tự động khi thay đổi select
        document.getElementById('sortSelect').addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    </script>
@endsection
