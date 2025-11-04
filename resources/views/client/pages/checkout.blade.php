@extends('client.layouts.master')

@section('title', 'Thanh Toán - CakeHome')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/cart">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán</li>
        </ol>
    </div>
</nav>

<!-- Checkout Section -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4"><i class="bi bi-credit-card"></i> Thanh Toán</h2>
        
        <div class="row g-4">
            <!-- Checkout Form -->
            <div class="col-lg-7">
                <form action="/orders/place" method="POST">
                    @csrf
                    
                    <!-- Customer Info -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0"><i class="bi bi-person"></i> Thông Tin Khách Hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Địa Chỉ Giao Hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-select" name="province" required>
                                        <option value="">Chọn...</option>
                                        <option value="hcm">TP. Hồ Chí Minh</option>
                                        <option value="hn">Hà Nội</option>
                                        <option value="dn">Đà Nẵng</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select class="form-select" name="district" required>
                                        <option value="">Chọn...</option>
                                        <option value="1">Quận 1</option>
                                        <option value="3">Quận 3</option>
                                        <option value="10">Quận 10</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                    <select class="form-select" name="ward" required>
                                        <option value="">Chọn...</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address" 
                                           placeholder="Số nhà, tên đường..." required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Time -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0"><i class="bi bi-clock"></i> Thời Gian Giao Hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Ngày giao</label>
                                    <input type="date" class="form-control" name="delivery_date" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Giờ giao</label>
                                    <select class="form-select" name="delivery_time">
                                        <option value="morning">Buổi sáng (8h-12h)</option>
                                        <option value="afternoon">Buổi chiều (14h-18h)</option>
                                        <option value="evening">Buổi tối (18h-21h)</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Ghi chú đơn hàng</label>
                                    <textarea class="form-control" name="note" rows="3" 
                                              placeholder="Ghi chú về đơn hàng, ví dụ: ghi chúc mừng sinh nhật..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary-custom text-white">
                            <h5 class="mb-0"><i class="bi bi-wallet2"></i> Phương Thức Thanh Toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    <strong>Thanh toán khi nhận hàng (COD)</strong><br>
                                    <small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label" for="bank_transfer">
                                    <strong>Chuyển khoản ngân hàng</strong><br>
                                    <small class="text-muted">Chuyển khoản qua Internet Banking</small>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="momo" value="momo">
                                <label class="form-check-label" for="momo">
                                    <strong>Ví MoMo</strong><br>
                                    <small class="text-muted">Thanh toán qua ví điện tử MoMo</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="vnpay" value="vnpay">
                                <label class="form-check-label" for="vnpay">
                                    <strong>VNPay</strong><br>
                                    <small class="text-muted">Thanh toán qua cổng VNPay</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label" for="agree">
                            Tôi đã đọc và đồng ý với <a href="/terms">Điều khoản dịch vụ</a> 
                            và <a href="/privacy">Chính sách bảo mật</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-custom btn-lg w-100">
                        <i class="bi bi-check-circle"></i> Đặt Hàng
                    </button>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Đơn Hàng Của Bạn</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        @for($i = 1; $i <= 3; $i++)
                        <div class="d-flex mb-3">
                            <img src="https://images.unsplash.com/photo-1578985545062?w=80" 
                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Bánh Chocolate Deluxe</h6>
                                <small class="text-muted">Số lượng: {{ $i }}</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format(450000 * $i, 0, ',', '.') }} đ</strong>
                            </div>
                        </div>
                        @endfor
                        
                        <hr>
                        
                        <!-- Voucher Input -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Mã giảm giá">
                                <button class="btn btn-outline-secondary">Áp dụng</button>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Price Summary -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
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
                            <strong class="fs-5">Tổng Cộng:</strong>
                            <strong class="text-primary-custom fs-4">2.430.000 đ</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Security Info -->
                <div class="card border-0 mt-3 bg-light">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check text-success fs-2"></i>
                        <p class="mb-0 mt-2"><strong>Thanh toán an toàn</strong></p>
                        <small class="text-muted">Thông tin được mã hóa SSL</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function placeOrder() {
        const form = document.getElementById('checkoutForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            showWarning('Thông tin chưa đầy đủ', 'Vui lòng điền đầy đủ thông tin giao hàng');
            return;
        }
        
        if (!document.getElementById('terms').checked) {
            showWarning('Chưa đồng ý', 'Vui lòng đồng ý với điều khoản và điều kiện');
            return;
        }
        
        Swal.fire({
            title: 'Xác nhận đặt hàng',
            html: `
                <div class="text-start">
                    <p><strong>Tổng tiền:</strong> 2.430.000 đ</p>
                    <p><strong>Phương thức thanh toán:</strong> ${getPaymentMethod()}</p>
                    <p>Bạn có chắc chắn muốn đặt hàng?</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B4513',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đặt hàng',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Simulate API call
                    setTimeout(() => {
                        resolve();
                    }, 2000);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Đặt hàng thành công!',
                    html: `
                        <div class="text-center">
                            <p>Cảm ơn bạn đã đặt hàng!</p>
                            <p>Mã đơn hàng: <strong>#DH${Math.floor(Math.random() * 10000)}</strong></p>
                            <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
                        </div>
                    `,
                    confirmButtonColor: '#8B4513',
                    confirmButtonText: 'Xem đơn hàng'
                }).then(() => {
                    // Redirect to order tracking or home
                    window.location.href = '/';
                });
            }
        });
    }
    
    function getPaymentMethod() {
        const selected = document.querySelector('input[name="payment"]:checked');
        if (!selected) return 'Chưa chọn';
        
        const methods = {
            'cod': 'Thanh toán khi nhận hàng (COD)',
            'card': 'Thanh toán bằng thẻ',
            'ewallet': 'Ví điện tử'
        };
        return methods[selected.value] || 'Chưa chọn';
    }
</script>
@endpush
