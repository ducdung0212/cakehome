@extends('client.layouts.master')

@section('title', 'Chi Tiết Sản Phẩm - CakeHome')

@push('styles')
<style>
    .product-image-main {
        height: 500px;
        object-fit: cover;
        border-radius: 15px;
    }
    .product-thumbnail {
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    .product-thumbnail:hover,
    .product-thumbnail.active {
        border-color: var(--primary-color);
    }
    .quantity-input {
        width: 60px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/products">Sản phẩm</a></li>
            <li class="breadcrumb-item active">Bánh Chocolate Deluxe</li>
        </ol>
    </div>
</nav>

<!-- Product Detail -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Product Images -->
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800" 
                     class="product-image-main w-100 mb-3" id="mainImage" alt="Product">
                <div class="row g-2">
                    <div class="col-3">
                        <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=200" 
                             class="product-thumbnail w-100 active" onclick="changeImage(this)">
                    </div>
                    <div class="col-3">
                        <img src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=200" 
                             class="product-thumbnail w-100" onclick="changeImage(this)">
                    </div>
                    <div class="col-3">
                        <img src="https://images.unsplash.com/photo-1535254973040-607b474cb50d?w=200" 
                             class="product-thumbnail w-100" onclick="changeImage(this)">
                    </div>
                    <div class="col-3">
                        <img src="https://images.unsplash.com/photo-1562440499-64c9a4d07de2?w=200" 
                             class="product-thumbnail w-100" onclick="changeImage(this)">
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <span class="badge bg-success mb-2">Còn Hàng</span>
                <h1 class="mb-3">Bánh Chocolate Deluxe</h1>
                
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
                    <h2 class="text-primary-custom d-inline">450.000 đ</h2>
                    <span class="text-muted text-decoration-line-through ms-2">550.000 đ</span>
                    <span class="badge bg-danger ms-2">-18%</span>
                </div>
                
                <div class="mb-4">
                    <h5>Mô Tả:</h5>
                    <p class="text-muted">
                        Bánh chocolate cao cấp với lớp kem mềm mịn, phủ socola Bỉ nguyên chất. 
                        Hương vị đậm đà, không quá ngọt, hoàn hảo cho mọi dịp đặc biệt.
                        Sản phẩm được làm thủ công từ nguyên liệu nhập khẩu chất lượng cao.
                    </p>
                </div>
                
                <div class="mb-4">
                    <h5>Thông Tin:</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> Kích thước: 20cm</li>
                        <li><i class="bi bi-check-circle text-success"></i> Trọng lượng: 1.2kg</li>
                        <li><i class="bi bi-check-circle text-success"></i> Phục vụ: 6-8 người</li>
                        <li><i class="bi bi-check-circle text-success"></i> Bảo quản: Tủ lạnh 3-5 ngày</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5>Kích Thước:</h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="size" id="size1" checked>
                        <label class="btn btn-outline-secondary" for="size1">15cm</label>
                        
                        <input type="radio" class="btn-check" name="size" id="size2">
                        <label class="btn btn-outline-secondary" for="size2">20cm</label>
                        
                        <input type="radio" class="btn-check" name="size" id="size3">
                        <label class="btn btn-outline-secondary" for="size3">25cm</label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Số Lượng:</h5>
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty()">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" class="form-control quantity-input" value="1" min="1" id="quantity">
                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQty()">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex">
                    <button class="btn btn-primary-custom btn-lg flex-fill" onclick="addToCart(1)">
                        <i class="bi bi-cart-plus"></i> Thêm Vào Giỏ
                    </button>
                    <button class="btn btn-outline-custom btn-lg">
                        <i class="bi bi-heart"></i>
                    </button>
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
                    <div id="description" class="tab-pane fade show active">
                        <h4>Thông Tin Chi Tiết</h4>
                        <p>Bánh Chocolate Deluxe là sản phẩm cao cấp nhất trong bộ sưu tập của chúng tôi. 
                        Được chế biến từ chocolate Bỉ nguyên chất kết hợp với công thức độc quyền, 
                        mang đến trải nghiệm vị giác tuyệt vời.</p>
                        
                        <h5 class="mt-4">Thành Phần:</h5>
                        <ul>
                            <li>Chocolate Bỉ cao cấp 70%</li>
                            <li>Bột mì nhập khẩu</li>
                            <li>Trứng gà tươi</li>
                            <li>Bơ New Zealand</li>
                            <li>Kem tươi Anchor</li>
                        </ul>
                    </div>
                    
                    <div id="reviews" class="tab-pane fade">
                        <h4 class="mb-4">Đánh Giá Khách Hàng</h4>
                        
                        @for($i = 1; $i <= 3; $i++)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <img src="https://i.pravatar.cc/50?img={{ $i }}" class="rounded-circle me-3" style="width: 50px; height: 50px;">
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
                                <p class="mb-0">Bánh rất ngon, kem mềm và chocolate đậm đà. Giao hàng đúng giờ và đóng gói cẩn thận!</p>
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
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="section-title">Sản Phẩm Liên Quan</h3>
            </div>
            @for($i = 1; $i <= 4; $i++)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="product-card card h-100">
                    <img src="https://images.unsplash.com/photo-{{ 1578985545062 + $i }}?w=400" 
                         class="product-image" alt="Product {{ $i }}">
                    <div class="card-body">
                        <h5 class="product-title">Bánh Chocolate {{ $i }}</h5>
                        <div class="product-rating mb-2">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">{{ number_format(400000 + $i * 50000, 0, ',', '.') }} đ</span>
                            <button class="btn btn-primary-custom btn-sm">
                                <i class="bi bi-cart-plus"></i>
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
    function changeImage(thumbnail) {
        document.getElementById('mainImage').src = thumbnail.src.replace('w=200', 'w=800');
        document.querySelectorAll('.product-thumbnail').forEach(img => {
            img.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
    
    function increaseQty() {
        let qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;
    }
    
    function decreaseQty() {
        let qty = document.getElementById('quantity');
        if(parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }
    
    function addToWishlist(productId) {
        showSuccess('Đã thêm vào yêu thích', 'Sản phẩm đã được thêm vào danh sách yêu thích');
    }
    
    function submitReview() {
        const rating = document.querySelector('input[name="rating"]:checked');
        const comment = document.getElementById('reviewComment').value.trim();
        
        if (!rating) {
            showWarning('Chưa đánh giá', 'Vui lòng chọn số sao đánh giá');
            return;
        }
        
        if (!comment) {
            showWarning('Chưa nhập nội dung', 'Vui lòng nhập nội dung đánh giá');
            return;
        }
        
        Swal.fire({
            title: 'Gửi đánh giá',
            text: 'Bạn có muốn gửi đánh giá này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B4513',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Gửi',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate API call
                Toast.fire({
                    icon: 'success',
                    title: 'Cảm ơn bạn!',
                    text: 'Đánh giá của bạn đã được gửi thành công'
                });
                
                // Clear form
                document.getElementById('reviewComment').value = '';
                const checkedRadio = document.querySelector('input[name="rating"]:checked');
                if (checkedRadio) checkedRadio.checked = false;
            }
        });
    }
</script>
@endpush
