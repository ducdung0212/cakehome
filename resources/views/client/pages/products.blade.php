@extends('client.layouts.master')

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
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4"><i class="bi bi-funnel"></i> Bộ Lọc</h5>
                        
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Danh Mục</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat1">
                                <label class="form-check-label" for="cat1">Bánh Kem</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat2">
                                <label class="form-check-label" for="cat2">Cookies</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat3">
                                <label class="form-check-label" for="cat3">Macaron</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat4">
                                <label class="form-check-label" for="cat4">Bánh Mì Ngọt</label>
                            </div>
                        </div>
                        
                        <!-- Price Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Khoảng Giá</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" id="price1">
                                <label class="form-check-label" for="price1">Dưới 200.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" id="price2">
                                <label class="form-check-label" for="price2">200.000đ - 500.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" id="price3">
                                <label class="form-check-label" for="price3">500.000đ - 1.000.000đ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" id="price4">
                                <label class="form-check-label" for="price4">Trên 1.000.000đ</label>
                            </div>
                        </div>
                        
                        <!-- Rating Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Đánh Giá</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="star5">
                                <label class="form-check-label" for="star5">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="star4">
                                <label class="form-check-label" for="star4">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i> trở lên
                                </label>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary-custom w-100">
                            <i class="bi bi-funnel"></i> Áp Dụng
                        </button>
                        <button class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-x-circle"></i> Xóa Bộ Lọc
                        </button>
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
            </div>
            
            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Sort Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Tất Cả Sản Phẩm <span class="text-muted">(48 sản phẩm)</span></h4>
                    <div class="d-flex align-items-center">
                        <label class="me-2">Sắp xếp:</label>
                        <select class="form-select" style="width: auto;">
                            <option>Mới nhất</option>
                            <option>Giá thấp đến cao</option>
                            <option>Giá cao đến thấp</option>
                            <option>Tên A-Z</option>
                            <option>Bán chạy</option>
                        </select>
                    </div>
                </div>
                
                <!-- Products -->
                <div class="row g-4">
                    @for($i = 1; $i <= 12; $i++)
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card card h-100">
                            @if($i % 4 == 0)
                            <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px; z-index: 10;">Hot</span>
                            @elseif($i % 3 == 0)
                            <span class="badge bg-success position-absolute" style="top: 10px; right: 10px; z-index: 10;">New</span>
                            @endif
                            
                            <div class="position-relative">
                                <img src="https://images.unsplash.com/photo-{{ 1578985545062 + $i }}?w=400&h=400&fit=crop" 
                                     class="product-image" alt="Product {{ $i }}">
                                <div class="position-absolute top-0 start-0 p-2">
                                    <button class="btn btn-light btn-sm rounded-circle" title="Yêu thích">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <span class="badge bg-light text-dark mb-2">Bánh Kem</span>
                                <h5 class="product-title">Bánh Chocolate {{ $i }}</h5>
                                <div class="product-rating mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                    <span class="text-muted">({{ 50 + $i * 10 }})</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="product-price">{{ number_format(300000 + $i * 50000, 0, ',', '.') }} đ</span>
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
                
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
@endsection
