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
                    <img src="{{ $product->firstImage ? asset('storage/' . $product->firstImage->image) : asset('images/no-image-product.png') }}"
                        class="product-image-main w-100 mb-3" id="mainImage" alt="{{ $product->name }}">
                    <div class="row g-2">
                        @foreach ($product->images as $image)
                            <div class="col-3">
                                <img src="{{ $image->image ? asset('storage/' . $image->image) : asset('images/no-image-product.png') }}"
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
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($reviewStats['average']))
                                    <i class="bi bi-star-fill"></i>
                                @elseif ($i <= ceil($reviewStats['average']) && $reviewStats['average'] - floor($reviewStats['average']) >= 0.5)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="text-muted ms-2">({{ $reviewStats['average'] }} sao - {{ $reviewStats['total'] }} đánh
                            giá)</span>
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
                        @include('client.components.addToCart-button', [
                            'productId' => $product->id,
                            'stock' => $product->stock,
                            'class' => 'ms-2',
                            'text' => 'Thêm vào giỏ',
                        ])
                        <div class="position-relative">
                            @include('client.components.wishlist-button', [
                                'productId' => $product->id,
                                'active' => in_array($product->id, $wishlistProductIds),
                            ])
                        </div>
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
                            <a class="nav-link" data-bs-toggle="tab" href="#reviews">Đánh Giá
                                ({{ $reviewStats['total'] }})</a>
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
                            <div class="row">
                                <!-- Reviews Summary -->
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h2 class="display-4 mb-0">{{ $reviewStats['average'] }}</h2>
                                            <div class="text-warning mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($reviewStats['average']))
                                                        <i class="bi bi-star-fill"></i>
                                                    @elseif ($i <= ceil($reviewStats['average']) && $reviewStats['average'] - floor($reviewStats['average']) >= 0.5)
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-muted mb-0">{{ $reviewStats['total'] }} đánh giá</p>
                                        </div>
                                        <div class="card-body border-top">
                                            @foreach ($reviewStats['distribution'] as $star => $data)
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="me-2" style="min-width: 60px;">{{ $star }} <i
                                                            class="bi bi-star-fill text-warning"></i></span>
                                                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                        <div class="progress-bar bg-warning"
                                                            style="width: {{ $data['percentage'] }}%"></div>
                                                    </div>
                                                    <span class="text-muted"
                                                        style="min-width: 40px;">{{ $data['count'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Reviews List -->
                                <div class="col-md-8">
                                    <h4 class="mb-4">Đánh Giá Khách Hàng</h4>

                                    @auth
                                        @if ($canReview)
                                            <!-- Review Form -->
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">Viết đánh giá của bạn</h5>
                                                    <form id="reviewForm">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                        <div class="mb-3">
                                                            <label class="form-label">Đánh giá của bạn</label>
                                                            <div class="rating-input">
                                                                @for ($i = 5; $i >= 1; $i--)
                                                                    <input type="radio" name="rating"
                                                                        value="{{ $i }}"
                                                                        id="star{{ $i }}" required>
                                                                    <label for="star{{ $i }}" class="star-label">
                                                                        <i class="bi bi-star-fill"></i>
                                                                    </label>
                                                                @endfor
                                                            </div>
                                                            <small class="text-danger d-none" id="ratingError">Vui lòng chọn
                                                                số sao</small>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="comment" class="form-label">Nhận xét</label>
                                                            <textarea class="form-control" id="comment" name="comment" rows="4" minlength="10" maxlength="500"
                                                                required placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm (tối thiểu 10 ký tự)"></textarea>
                                                            <div class="d-flex justify-content-between">
                                                                <small class="text-danger d-none" id="commentError"></small>
                                                                <small class="text-muted ms-auto"><span
                                                                        id="charCount">0</span>/500</small>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary-custom"
                                                            id="submitReviewBtn">
                                                            <i class="bi bi-send"></i> Gửi đánh giá
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @elseif (Auth::user()->reviews()->where('product_id', $product->id)->exists())
                                            <div class="alert alert-info mb-4">
                                                <i class="bi bi-info-circle"></i> Bạn đã đánh giá sản phẩm này rồi.
                                            </div>
                                        @else
                                            <div class="alert alert-warning mb-4">
                                                <i class="bi bi-exclamation-triangle"></i> Bạn cần mua sản phẩm này để có thể
                                                đánh giá.
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-info mb-4">
                                            <i class="bi bi-info-circle"></i> Vui lòng <a href="{{ route('login') }}">đăng
                                                nhập</a> để viết đánh giá.
                                        </div>
                                    @endauth

                                    <!-- User's Pending Review -->
                                    @if (isset($userPendingReview) && $userPendingReview)
                                        <div class="card mb-3 border-warning">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock-history"></i> Đánh giá của bạn đang chờ duyệt
                                                    </span>
                                                    <small
                                                        class="text-muted">{{ $userPendingReview->created_at->diffForHumans() }}</small>
                                                </div>
                                                <div class="d-flex">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($userPendingReview->user->name) }}&background=random"
                                                        class="rounded-circle me-3" style="width: 50px; height: 50px;">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $userPendingReview->user->name }} <span
                                                                class="text-muted small">(Bạn)</span></h6>
                                                        <div class="text-warning mb-2">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $userPendingReview->rating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <p class="mb-0">{{ $userPendingReview->comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Reviews List -->
                                    @forelse ($reviews as $review)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random"
                                                            class="rounded-circle me-3"
                                                            style="width: 50px; height: 50px;">
                                                        <div>
                                                            <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                            <div class="text-warning mb-2">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $review->rating)
                                                                        <i class="bi bi-star-fill"></i>
                                                                    @else
                                                                        <i class="bi bi-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="bi bi-chat-square-text text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">Chưa có đánh giá nào cho sản phẩm này.</p>
                                        </div>
                                    @endforelse

                                    <!-- Pagination -->
                                    @if ($reviews->hasPages())
                                        <div class="mt-4">
                                            {{ $reviews->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
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
                                            <img src="{{ $product->firstImage ? asset('storage/' . $product->firstImage->image) : asset('images/no-image-product.png') }}"
                                                class="product-image" alt="{{ $product->name }}"
                                                style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="product-title text-truncate">{{ $product->name }}</h5>
                                                <div class="product-rating mb-2">
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
                                                    <small class="text-muted">{{ $product->reviews_count ?? 0 }}</small>
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
