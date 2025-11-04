@extends('client.layouts.master')

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
            <div class="col-lg-8">
                @for($i = 1; $i <= 3; $i++)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="https://images.unsplash.com/photo-1578985545062?w=150" 
                                     class="img-fluid rounded" alt="Product">
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-1">Bánh Chocolate Deluxe</h5>
                                <p class="text-muted mb-0">Kích thước: 20cm</p>
                                <small class="text-success"><i class="bi bi-check-circle"></i> Còn hàng</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-0 fw-bold">{{ number_format(450000, 0, ',', '.') }} đ</p>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control text-center" value="{{ $i }}" min="1">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <p class="mb-2 fw-bold text-primary-custom">{{ number_format(450000 * $i, 0, ',', '.') }} đ</p>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
                
                <div class="d-flex justify-content-between">
                    <a href="/products" class="btn btn-outline-custom">
                        <i class="bi bi-arrow-left"></i> Tiếp Tục Mua Sắm
                    </a>
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i> Xóa Tất Cả
                    </button>
                </div>
            </div>
            
            <!-- Order Summary -->
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
        </div>
    </div>
</section>

<!-- You May Like -->
<section class="py-5 bg-light-custom">
    <div class="container">
        <h3 class="section-title">Có Thể Bạn Thích</h3>
        <div class="row g-4">
            @for($i = 1; $i <= 4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="product-card card h-100">
                    <img src="https://images.unsplash.com/photo-{{ 1578985545062 + $i }}?w=400" 
                         class="product-image" alt="Product">
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
                            <span class="product-price">450.000 đ</span>
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
    function updateQuantity(itemId, change) {
        const input = document.querySelector(`input[data-item-id="${itemId}"]`);
        let quantity = parseInt(input.value);
        quantity = Math.max(1, quantity + change);
        input.value = quantity;
        
        // Update price
        updateItemPrice(itemId);
        updateCartTotal();
        
        showSuccess('Đã cập nhật', 'Số lượng sản phẩm đã được cập nhật');
    }
    
    function removeFromCart(itemId) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8B4513',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, xóa đi!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
                if (row) {
                    row.remove();
                }
                updateCartTotal();
                updateCartCount(-1);
                
                Toast.fire({
                    icon: 'success',
                    title: 'Đã xóa',
                    text: 'Sản phẩm đã được xóa khỏi giỏ hàng'
                });
            }
        });
    }
    
    function applyVoucher() {
        const voucherCode = document.getElementById('voucherCode').value.trim();
        
        if (!voucherCode) {
            showWarning('Thiếu mã', 'Vui lòng nhập mã giảm giá');
            return;
        }
        
        // Simulate voucher validation
        if (voucherCode.toUpperCase() === 'CAKE2024') {
            showSuccess('Áp dụng thành công', 'Bạn được giảm giá 50.000đ');
            // Update discount amount
            document.getElementById('discountAmount').textContent = '50.000đ';
            updateCartTotal();
        } else {
            showError('Mã không hợp lệ', 'Mã giảm giá không tồn tại hoặc đã hết hạn');
        }
    }
    
    function updateItemPrice(itemId) {
        const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
        if (!row) return;
        
        const quantity = parseInt(row.querySelector('input').value);
        const price = parseInt(row.querySelector('.item-price').textContent.replace(/[^0-9]/g, ''));
        const totalPrice = quantity * price;
        
        row.querySelector('.item-total').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
    }
    
    function updateCartTotal() {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            subtotal += parseInt(el.textContent.replace(/[^0-9]/g, ''));
        });
        
        const shipping = 30000;
        const discount = parseInt(document.getElementById('discountAmount').textContent.replace(/[^0-9]/g, '')) || 0;
        const total = subtotal + shipping - discount;
        
        document.getElementById('subtotal').textContent = subtotal.toLocaleString('vi-VN') + 'đ';
        document.getElementById('total').textContent = total.toLocaleString('vi-VN') + 'đ';
    }
</script>
@endpush
