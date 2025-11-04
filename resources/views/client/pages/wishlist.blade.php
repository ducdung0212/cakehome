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
            <span class="text-muted">(5 sản phẩm)</span>
        </div>
        
        <!-- Wishlist Items -->
        <div class="row g-4">
            @for($i = 1; $i <= 5; $i++)
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="product-card card h-100 position-relative">
                    <!-- Remove from Wishlist Button -->
                    <button class="btn btn-light btn-sm position-absolute" 
                            style="top: 10px; right: 10px; z-index: 10; border-radius: 50%; width: 40px; height: 40px;"
                            onclick="removeFromWishlist({{ $i }})"
                            title="Xóa khỏi yêu thích">
                        <i class="bi bi-heart-fill text-danger"></i>
                    </button>
                    
                    @if($i % 3 == 0)
                    <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 10;">Sale 20%</span>
                    @endif
                    
                    <img src="https://images.unsplash.com/photo-{{ 1578985545062 + $i }}?w=400&h=400&fit=crop" 
                         class="product-image" alt="Product {{ $i }}">
                    
                    <div class="card-body">
                        <span class="badge bg-light text-dark mb-2">Bánh Kem</span>
                        <h5 class="product-title">Bánh Chocolate Deluxe {{ $i }}</h5>
                        <div class="product-rating mb-2">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                            <span class="text-muted">({{ 50 + $i * 10 }})</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                @if($i % 3 == 0)
                                <span class="product-price">360.000 đ</span>
                                <small class="text-muted text-decoration-line-through d-block">450.000 đ</small>
                                @else
                                <span class="product-price">{{ number_format(400000 + $i * 50000, 0, ',', '.') }} đ</span>
                                @endif
                            </div>
                            <span class="badge {{ $i % 2 == 0 ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $i % 2 == 0 ? 'Còn hàng' : 'Sắp hết' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary-custom" onclick="addToCart({{ $i }})">
                                <i class="bi bi-cart-plus"></i> Thêm Vào Giỏ
                            </button>
                            <a href="/products/{{ $i }}" class="btn btn-outline-custom btn-sm">
                                <i class="bi bi-eye"></i> Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        
        <!-- Empty Wishlist State (Hidden by default, show when no items) -->
        <div class="text-center py-5 d-none" id="emptyWishlist">
            <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-4">Danh Sách Yêu Thích Trống</h3>
            <p class="text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích</p>
            <a href="/products" class="btn btn-primary-custom btn-lg mt-3">
                <i class="bi bi-shop"></i> Khám Phá Sản Phẩm
            </a>
        </div>
        
        <!-- Actions -->
        <div class="row mt-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="/products" class="btn btn-outline-custom">
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
    </div>
</section>

<!-- Share Wishlist Section -->
<section class="py-5 bg-light-custom">
    <div class="container">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <h4 class="mb-3"><i class="bi bi-share"></i> Chia Sẻ Danh Sách Yêu Thích</h4>
                <p class="text-muted">Chia sẻ danh sách yêu thích của bạn với bạn bè và người thân</p>
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-facebook"></i> Facebook
                    </button>
                    <button class="btn btn-outline-info">
                        <i class="bi bi-twitter"></i> Twitter
                    </button>
                    <button class="btn btn-outline-success">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </button>
                    <button class="btn btn-outline-secondary" onclick="copyWishlistLink()">
                        <i class="bi bi-link-45deg"></i> Sao Chép Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recommendations -->
<section class="py-5">
    <div class="container">
        <h3 class="section-title">Có Thể Bạn Cũng Thích</h3>
        <div class="row g-4">
            @for($i = 1; $i <= 4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="product-card card h-100">
                    <img src="https://images.unsplash.com/photo-{{ 1578985545062 + $i + 10 }}?w=400" 
                         class="product-image" alt="Product">
                    <div class="card-body">
                        <h5 class="product-title">Bánh Strawberry {{ $i }}</h5>
                        <div class="product-rating mb-2">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">{{ number_format(350000 + $i * 50000, 0, ',', '.') }} đ</span>
                            <button class="btn btn-light btn-sm rounded-circle" onclick="addToWishlist({{ $i + 10 }})">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function removeFromWishlist(productId) {
        Swal.fire({
            title: 'Xóa khỏi yêu thích',
            text: 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8B4513',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, xóa đi!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Removed product ' + productId + ' from wishlist');
                // Add your AJAX call here
                
                Toast.fire({
                    icon: 'success',
                    title: 'Đã xóa',
                    text: 'Sản phẩm đã được xóa khỏi danh sách yêu thích'
                });
            }
        });
    }
    
    function addAllToCart() {
        Swal.fire({
            title: 'Thêm tất cả vào giỏ hàng',
            text: 'Bạn có muốn thêm tất cả sản phẩm vào giỏ hàng?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B4513',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Thêm tất cả',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 1000);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const productCount = 6; // Count from grid
                
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: `Đã thêm ${productCount} sản phẩm vào giỏ hàng`,
                    confirmButtonColor: '#8B4513'
                });
                
                updateCartCount(productCount);
            }
        });
    }
    
    function clearWishlist() {
        Swal.fire({
            title: 'Xóa tất cả',
            text: 'Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi danh sách yêu thích?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xóa tất cả',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Clearing wishlist');
                // Add your AJAX call here
                
                Toast.fire({
                    icon: 'success',
                    title: 'Đã xóa tất cả',
                    text: 'Danh sách yêu thích đã được làm trống'
                });
                
                // Show empty state
                document.getElementById('emptyWishlist').classList.remove('d-none');
            }
        });
    }
    
    function addToWishlist(productId) {
        showSuccess('Đã thêm vào yêu thích', 'Sản phẩm đã được thêm vào danh sách yêu thích');
    }
    
    function copyWishlistLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            Toast.fire({
                icon: 'success',
                title: 'Đã sao chép',
                text: 'Link đã được sao chép vào clipboard'
            });
        }).catch(() => {
            showError('Lỗi', 'Không thể sao chép link');
        });
    }
</script>
@endpush
