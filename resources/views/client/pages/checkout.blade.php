@extends('client.layouts.master')

@push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('title', 'Thanh Toán - CakeHome')

@section('content')
    @php
        $showVouchers = ($siteSettings['client_show_vouchers'] ?? '1') === '1';
        $shippingFeeCfg = (float) ($siteSettings['shipping_fee'] ?? 30000);
        $freeShipThresholdCfg = (float) ($siteSettings['free_shipping_threshold'] ?? 500000);
        $storeAddress = $siteSettings['site_address'] ?? '62 Đ.Số 15, Bình Hưng, Bình Chánh';
        $workHours = $siteSettings['site_working_hours'] ?? '8:00 - 20:00 hàng ngày';
    @endphp
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

            <!-- Hiển thị lỗi validation -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Có lỗi xảy ra!</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Checkout Form -->
                <div class="col-lg-7">
                    <form action="{{ route('orders.place') }}" method="POST">
                        @csrf

                        @if ($showVouchers)
                            <input type="hidden" name="voucher_code"
                                value="{{ old('voucher_code', $voucher_code ?? '') }}">
                        @endif

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

                        <!-- Delivery Method -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary-custom text-white">
                                <h5 class="mb-0"><i class="bi bi-truck"></i> Phương Thức Nhận Hàng</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="delivery"
                                        value="delivery" checked onchange="toggleDeliveryFields()">
                                    <label class="form-check-label" for="delivery">
                                        <strong><i class="bi bi-truck text-primary"></i> Giao hàng tận nơi</strong><br>
                                        <small class="text-muted">
                                            Nhận hàng tại địa chỉ của bạn (Phí ship:
                                            {{ number_format($shippingFeeCfg, 0, ',', '.') }}đ,
                                            miễn phí từ {{ number_format($freeShipThresholdCfg, 0, ',', '.') }}đ)
                                        </small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_method" id="pickup"
                                        value="pickup" onchange="toggleDeliveryFields()">
                                    <label class="form-check-label" for="pickup">
                                        <strong><i class="bi bi-shop text-success"></i> Nhận tại cửa hàng</strong><br>
                                        <small class="text-muted">Đến cửa hàng để lấy bánh (Miễn phí ship)</small>
                                    </label>
                                </div>
                                <div class="alert alert-info mt-3" id="pickup_info" style="display: none;">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Địa chỉ cửa hàng:</strong><br>
                                    {{ $storeAddress }}<br>
                                    <small>Giờ làm việc: {{ $workHours }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="card mb-4" id="shipping_address_card">
                            <div class="card-header bg-primary-custom text-white">
                                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Địa Chỉ Giao Hàng</h5>
                            </div>
                            <div class="card-body">
                                <!-- Address Selection Toggle -->
                                <div class="mb-4">
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="address_type" id="saved_address"
                                            value="saved" {{ $addresses->count() > 0 ? 'checked' : '' }}
                                            {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-primary" for="saved_address">
                                            <i class="bi bi-bookmark"></i> Địa chỉ đã lưu
                                        </label>

                                        <input type="radio" class="btn-check" name="address_type" id="new_address"
                                            value="new" {{ $addresses->count() == 0 ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="new_address">
                                            <i class="bi bi-plus-circle"></i> Địa chỉ mới
                                        </label>
                                    </div>
                                </div>

                                <!-- Saved Addresses Section -->
                                <div id="saved_addresses_section"
                                    style="display: {{ $addresses->count() > 0 ? 'block' : 'none' }};">
                                    @if ($addresses->count() > 0)
                                        <div class="mb-3">
                                            <label class="form-label">Chọn địa chỉ giao hàng <span
                                                    class="text-danger">*</span></label>
                                            <div class="list-group">
                                                @foreach ($addresses as $address)
                                                    <label class="list-group-item list-group-item-action">
                                                        <div class="d-flex align-items-start">
                                                            <input class="form-check-input me-3 mt-1" type="radio"
                                                                name="saved_address_id" value="{{ $address->id }}"
                                                                {{ ($defaultAddress && $address->id == $defaultAddress->id) || $loop->first ? 'checked' : '' }}>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <strong>{{ $address->full_name }}</strong>
                                                                    @if ($address->default)
                                                                        <span class="badge bg-success ms-2">Mặc định</span>
                                                                    @endif
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <div><i class="bi bi-telephone"></i>
                                                                        {{ $address->phone_number }}</div>
                                                                    <div><i class="bi bi-geo-alt"></i>
                                                                        {{ $address->address }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> Bạn chưa có địa chỉ đã lưu. Vui lòng nhập địa
                                            chỉ mới hoặc
                                            <a href="{{ route('account.addresses') }}" class="alert-link">thêm địa chỉ
                                                vào
                                                tài khoản</a>.
                                        </div>
                                    @endif
                                </div>

                                <!-- New Address Section -->
                                <div id="new_address_section"
                                    style="display: {{ $addresses->count() == 0 ? 'block' : 'none' }};">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Người nhận <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="receiver_name"
                                                value="{{ auth()->user()->name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Số điện thoại <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="receiver_phone"
                                                value="{{ auth()->user()->phone }}">
                                        </div>
                                        @include('client.components.location-fields', [
                                            'prefix' => '',
                                            'provinceValue' => '',
                                            'districtValue' => '',
                                            'wardValue' => '',
                                            'required' => false,
                                        ])
                                        <div class="col-12">
                                            <label class="form-label">Địa chỉ cụ thể <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="address"
                                                placeholder="Số nhà, tên đường...">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="save_address"
                                                    id="save_address" value="1">
                                                <label class="form-check-label" for="save_address">
                                                    Lưu địa chỉ này vào tài khoản
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Time -->
                        <div class="card mb-4" id="delivery_time_card">
                            <div class="card-header bg-primary-custom text-white">
                                <h5 class="mb-0"><i class="bi bi-clock"></i> Thời Gian Giao Hàng</h5>
                            </div>
                            <div class="card-body">
                                <!-- Option Giao Ngay -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="delivery_now"
                                            id="delivery_now" value="1">
                                        <label class="form-check-label fw-bold" for="delivery_now">
                                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                                            Giao ngay khi có thể
                                        </label>
                                        <small class="text-muted d-block">Shop sẽ chuẩn bị và giao hàng nhanh nhất có
                                            thể</small>
                                    </div>
                                </div>

                                <div id="scheduled_delivery_section">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Thời gian nhận bánh mong muốn <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="delivery_time" name="delivery_at" class="form-control"
                                            placeholder="Chọn ngày giờ...">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i>
                                            Vui lòng đặt trước ít nhất 2 tiếng. Giờ giao hàng: 8:00 - 20:00
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Ghi chú đơn hàng</label>
                                        <textarea class="form-control" name="notes" rows="3"
                                            placeholder="Ghi chú về đơn hàng, ví dụ: ghi chúc mừng sinh nhật, viết chữ lên bánh..."></textarea>
                                        <small class="text-muted">
                                            <i class="bi bi-pencil"></i>
                                            Ví dụ: "Viết chữ Happy Birthday", "Không bỏ nến"...
                                        </small>
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
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                        value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <strong>Thanh toán khi nhận hàng (COD)</strong><br>
                                        <small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="momo"
                                        value="momo">
                                    <label class="form-check-label" for="momo">
                                        <strong>Ví MoMo</strong><br>
                                        <small class="text-muted">Thanh toán qua ví điện tử MoMo</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-credit-card"></i> Đặt Hàng & Thanh Toán
                            </button>
                        </div>
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
                            @foreach ($cartItems as $item)
                                <div class="d-flex mb-3">
                                    <img src="{{ $item->product->firstImage ? asset('storage/' . $item->product->firstImage->image) : asset('images/no-image-product.png') }}"
                                        class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                        <small class="text-muted">{{ $item->quantity }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{ number_format($item->product->price, 0, ',', '.') }}VND</strong>
                                    </div>
                                </div>
                            @endforeach
                            <hr>

                            <!-- Voucher Input -->
                            @if ($showVouchers)
                                <div class="mb-3">
                                    <form action="{{ route('checkout.index') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="voucher_code"
                                                placeholder="Mã giảm giá"
                                                value="{{ old('voucher_code', $voucher_code ?? '') }}">
                                            <button class="btn btn-outline-secondary" type="submit">Áp dụng</button>
                                        </div>
                                        @if (!empty($voucher_error))
                                            <small class="text-danger d-block mt-2">{{ $voucher_error }}</small>
                                        @endif
                                    </form>
                                </div>

                                <hr>
                            @endif

                            <hr>

                            <!-- Price Summary -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính (<span id="total-quantity">{{ $totalQuantity }}</span> sản phẩm):</span>
                                <span id="subtotal">{{ number_format($subtotal_price, 0, ',', '.') }} VND</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="discount-row"
                                style="{{ $discount_amount > 0 ? '' : 'display: none;' }}">
                                <span>Giảm giá:</span>
                                <span class="text-success"
                                    id="discount">-{{ number_format($discount_amount, 0, ',', '.') }}
                                    VND</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span id="shipping-fee" class="{{ $shippingFee == 0 ? 'text-success' : '' }}">
                                    {{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . ' VND' }}
                                </span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng Cộng:</strong>
                                <strong class="text-primary-custom fs-4"
                                    id="total">{{ number_format($total_price, 0, ',', '.') }}
                                    VND</strong>
                            </div>

                            <!-- Nút thanh toán đã chuyển vào trong form ở cột bên trái -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('assets/client/js/location-cascade.js') }}"></script>

    <script src="{{ asset('assets/client/js/checkout.js') }}"></script>
@endpush
