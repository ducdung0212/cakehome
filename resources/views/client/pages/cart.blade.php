@extends('client.layouts.master')
@push('scripts')
    <script src="{{ asset('assets/client/js/cart.js') }}"></script>
@endpush
@section('title', 'Giỏ Hàng - CakeHome')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="bg-light py-3">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Giỏ hàng</li>
            </ol>
        </div>
    </nav>

    <!-- Cart Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4"><i class="bi bi-cart3"></i> Giỏ Hàng Của Bạn</h2>

            <div class="row g-4">
                <!-- Cart Items -->
                @if ($cartItems->count() > 0)
                    <div class="col-lg-8">
                        @foreach ($cartItems as $item)
                            <div class="card mb-3" id="cart-item-{{ $item->product_id }}">
                                <div class="card-body position-relative">
                                    <!-- Icon xóa góc trên bên phải -->
                                    <button type="button" class="btn btn-link position-absolute top-0 end-0 m-2 p-1"
                                        onclick="removeFromCart({{ $item->product_id }}, this)"
                                        style="color: #8B4513; font-size: 1.2rem;" title="Xóa sản phẩm">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="{{ $item->product->firstImage ? asset('storage/Product/' . $item->product->firstImage->image) : asset('images/no-image-product.png') }}"
                                                class="img-fluid rounded" alt="Product">
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                                            <p class="text-muted mb-0">Kích thước: 20cm</p>
                                            @if ($item->product->stock > $item->quantity)
                                                <small class="text-success"><i class="bi bi-check-circle"></i> Còn
                                                    hàng({{ $item->product->stock }})</small>
                                            @else
                                                <small class="text-failed"><i class="bg-warning text-dark"></i>Không đủ số
                                                    lượng({{ $item->product->stock }})</small>
                                            @endif



                                        </div>
                                        <div class="col-md-2 text-center">
                                            <p class="mb-0 fw-bold">
                                                {{ number_format($item->product->price, 0, ',', '.') }}VND</p>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="updateQuantity({{ $item->product_id }}, {{ $item->quantity }}, 'decrease', {{ $item->product->stock }})">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text" class="form-control text-center"
                                                    id="qty-{{ $item->product_id }}" value="{{ $item->quantity }}"
                                                    readonly style="background-color: white;">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="updateQuantity({{ $item->product_id }}, {{ $item->quantity }}, 'increase', {{ $item->product->stock }})">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach



                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/products" class="btn btn-outline-custom">
                                <i class="bi bi-arrow-left"></i> Tiếp Tục Mua Sắm
                            </a>
                            <button class="btn btn-outline-danger" onclick="clearCart()">
                                <i class="bi bi-trash"></i> Xóa Tất Cả
                            </button>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-5">
                            {{ $cartItems->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @else
                    <!-- Giỏ hàng trống -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm text-center py-5">
                            <div class="card-body">
                                <i class="bi bi-cart-x" style="font-size: 5rem; color: #ddd;"></i>
                                <h3 class="mt-4 mb-3">Giỏ hàng của bạn đang trống</h3>
                                <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm!</p>
                                <a href="/products" class="btn btn-primary-custom btn-lg">
                                    <i class="bi bi-arrow-left"></i> Tiếp Tục Mua Sắm
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Summary -->
                @if ($cartItems->count() > 0)
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Tóm Tắt Đơn Hàng</h5>

                                <!-- Voucher -->
                                <div class="mb-3">
                                    <label class="form-label">Mã Giảm Giá</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Nhập mã">
                                        <button class="btn btn-primary-custom">Áp Dụng</button>
                                    </div>
                                </div>

                                <hr>

                                <!-- Price Details -->
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính (3 sản phẩm):</span>
                                    <span>2.700.000 đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giảm giá:</span>
                                    <span class="text-success">-270.000 đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span class="text-success">Miễn phí</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Tổng Cộng:</strong>
                                    <strong class="text-primary-custom fs-4">2.430.000 đ</strong>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="/checkout" class="btn btn-primary-custom btn-lg">
                                        <i class="bi bi-credit-card"></i> Thanh Toán
                                    </a>
                                    <button class="btn btn-outline-custom">
                                        <i class="bi bi-paypal"></i> PayPal
                                    </button>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-check text-success"></i>
                                        <strong>Thanh toán an toàn</strong><br>
                                        Thông tin của bạn được mã hóa và bảo mật
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
