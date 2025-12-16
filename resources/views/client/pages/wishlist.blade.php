@extends('client.layouts.master')

@section('title', 'Danh Sách Yêu Thích - CakeHome')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-3">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Danh sách yêu thích</li>
            </ol>
        </div>
    </nav>

    <!-- Wishlist Section -->

    <section class="py-5">

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-heart-fill text-danger"></i> Danh Sách Yêu Thích</h2>
                <span class="text-muted">{{ $wishlists->total() }} sản phẩm</span>
            </div>

            @if ($wishlists->total() > 0)
                <!-- Wishlist Items -->

                <div class="row g-4">
                    @foreach ($wishlists as $wishlist)
                        <div class="col-lg-3 col-md-6 col-sm-6" id="wishlist-item-{{ $wishlist->product->id }}">
                            <div class="product-card card h-100 position-relative">
                                <!-- Remove from Wishlist Button -->
                                <div class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                    @include('client.components.wishlist-button', [
                                        'productId' => $wishlist->product->id,
                                        'active' => true,
                                    ])
                                </div>
                                <img src="{{ $wishlist->product->firstImage ? asset('storage/' . $wishlist->product->firstImage->image) : asset('images/no-image-product.png') }}"
                                    class="product-image" alt="{{ $wishlist->product->name }}">
                                <div class="card-body">
                                    <span
                                        class="badge bg-light text-dark mb-2">{{ $wishlist->product->category->name }}</span>
                                    <h5 class="product-title">{{ $wishlist->product->name }}</h5>
                                    <div class="product-rating mb-2">
                                        <span class="text-warning">
                                            @php
                                                $avgRating = round($wishlist->product->reviews_avg ?? 0, 1);
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
                                        <small class="text-muted">({{ $wishlist->product->reviews_count ?? 0 }})</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            @if ($wishlist->product->sale_price && $wishlist->product->sale_price < $wishlist->product->price)
                                                <span
                                                    class="product-price text-danger fw-bold">{{ number_format($wishlist->product->sale_price, 0, ',', '.') }}VNĐ</span>
                                                <small
                                                    class="text-muted text-decoration-line-through d-block">{{ number_format($wishlist->product->price, 0, ',', '.') }}
                                                    đ</small>
                                            @else
                                                <span
                                                    class="product-price">{{ number_format($wishlist->product->price, 0, ',', '.') }}VNĐ</span>
                                            @endif
                                        </div>
                                        <span
                                            class="badge {{ $wishlist->product->status == 'in_stock' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $wishlist->product->status == 'in_stock' ? 'Còn hàng' : 'Sắp hết' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-grid gap-2">
                                        @include('client.components.addToCart-button', [
                                            'productId' => $wishlist->product->id,
                                            'stock' => $wishlist->product->stock,
                                            'class' => 'ms-2',
                                            'text' => 'Thêm vào giỏ',
                                        ])
                                        <a href="/products/" class="btn btn-outline-custom btn-sm">
                                            <i class="bi bi-eye"></i> Chi Tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <nav aria-label="Page navigation" class="mt-5">
                    {{ $wishlists->appends(request()->query())->links() }}
                </nav>
            @else
                <!-- Empty Wishlist State -->
                <div class="text-center py-5">
                    <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
                    <h3 class="mt-4">Danh Sách Yêu Thích Trống</h3>
                    <p class="text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary-custom btn-lg mt-3">
                        <i class="bi bi-shop"></i> Khám Phá Sản Phẩm
                    </a>
                </div>
            @endif


            @if ($wishlists->total() > 0)
                <!-- Actions -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left"></i> Tiếp Tục Mua Sắm
                        </a>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-primary-custom me-2" onclick="addAllToCart()">
                            <i class="bi bi-cart-plus"></i> Thêm Tất Cả Vào Giỏ
                        </button>
                        <button class="btn btn-outline-danger" onclick="clearWishlist()">
                            <i class="bi bi-trash"></i> Xóa Tất Cả
                        </button>
                    </div>
                </div>
            @endif
        </div>

    </section>

    @push('scripts')
        <script>
            function addAllToCart() {
                if (confirm('Đồng ý thêm tất cả sản phẩm vào giỏ hàng?')) {
                    fetch('/wishlist/add-all-to-cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success(data.message);
                                // Cập nhật số lượng giỏ hàng nếu có
                                setTimeout(() => {
                                    window.location.href = '/cart';
                                }, 1500);
                            } else {
                                toastr.error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Có lỗi xảy ra!');
                        });
                }
            }

            function clearWishlist() {
                if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi danh sách yêu thích?')) {
                    fetch('/wishlist/clear-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success(data.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                toastr.error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Có lỗi xảy ra!');
                        });
                }
            }
        </script>
    @endpush
@endsection
