@extends('client.layouts.master')

@section('title', 'Về Chúng Tôi - CakeHome')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background-image: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=1600'); height: 400px;">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title" style="font-size: 3rem;">VỀ CHÚNG TÔI</h1>
        <p class="hero-subtitle">Câu chuyện về CakeHome</p>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=600" 
                     class="img-fluid rounded shadow-lg" alt="About Us">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Câu Chuyện Của Chúng Tôi</h2>
                <p class="lead">
                    CakeHome được thành lập vào năm 2020 với sứ mệnh mang đến những chiếc bánh 
                    ngọt cao cấp, được làm thủ công với tình yêu và đam mê.
                </p>
                <p>
                    Chúng tôi tin rằng mỗi chiếc bánh không chỉ là món ăn, mà còn là cầu nối 
                    tình cảm giữa người với người. Đó là lý do chúng tôi luôn tâm huyết trong 
                    từng sản phẩm, từ việc chọn lựa nguyên liệu đến khâu hoàn thiện cuối cùng.
                </p>
                <p>
                    Với đội ngũ đầu bếp chuyên nghiệp được đào tạo bài bản, CakeHome tự hào là 
                    một trong những thương hiệu bánh ngọt cao cấp hàng đầu tại Việt Nam.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-5 bg-light-custom">
    <div class="container">
        <h2 class="section-title">Giá Trị Cốt Lõi</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-heart text-primary-custom" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Tâm Huyết</h4>
                    <p class="text-muted">
                        Mỗi sản phẩm đều được làm với tình yêu và sự cẩn thận tối đa
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-award text-primary-custom" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Chất Lượng</h4>
                    <p class="text-muted">
                        Cam kết sử dụng nguyên liệu nhập khẩu chất lượng cao nhất
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <i class="bi bi-people text-primary-custom" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Khách Hàng</h4>
                    <p class="text-muted">
                        Sự hài lòng của khách hàng là ưu tiên hàng đầu của chúng tôi
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Đội Ngũ Của Chúng Tôi</h2>
        <div class="row g-4">
            @for($i = 1; $i <= 4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <img src="https://i.pravatar.cc/300?img={{ $i }}" class="card-img-top" alt="Team">
                    <div class="card-body text-center">
                        <h5>Nguyễn Văn {{ $i == 1 ? 'A' : ($i == 2 ? 'B' : ($i == 3 ? 'C' : 'D')) }}</h5>
                        <p class="text-muted mb-0">
                            {{ $i == 1 ? 'Founder & CEO' : ($i == 2 ? 'Head Chef' : ($i == 3 ? 'Marketing Manager' : 'Operations Manager')) }}
                        </p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Achievements -->
<section class="py-5 bg-primary-custom text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <h2 class="display-4 fw-bold">5+</h2>
                <p>Năm Kinh Nghiệm</p>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <h2 class="display-4 fw-bold">10K+</h2>
                <p>Khách Hàng Hài Lòng</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="display-4 fw-bold">50+</h2>
                <p>Loại Sản Phẩm</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="display-4 fw-bold">15+</h2>
                <p>Giải Thưởng</p>
            </div>
        </div>
    </div>
</section>
@endsection
