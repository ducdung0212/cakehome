@extends('client.layouts.master')

@section('title', 'Liên Hệ - CakeHome')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active">Liên hệ</li>
        </ol>
    </div>
</nav>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <h2 class="mb-4">Gửi Tin Nhắn Cho Chúng Tôi</h2>
                <form action="/contact/send" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Chủ đề</label>
                        <select class="form-select" name="subject">
                            <option value="">Chọn chủ đề</option>
                            <option value="order">Đơn hàng</option>
                            <option value="product">Sản phẩm</option>
                            <option value="custom">Đặt bánh theo yêu cầu</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" rows="5" 
                                  placeholder="Nhập nội dung tin nhắn..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-send"></i> Gửi Tin Nhắn
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-6">
                <h2 class="mb-4">Thông Tin Liên Hệ</h2>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5><i class="bi bi-geo-alt text-primary-custom"></i> Địa Chỉ</h5>
                        <p class="mb-0">123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5><i class="bi bi-telephone text-primary-custom"></i> Điện Thoại</h5>
                        <p class="mb-0">Hotline: <a href="tel:1900xxxx">1900-xxxx</a></p>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5><i class="bi bi-envelope text-primary-custom"></i> Email</h5>
                        <p class="mb-0"><a href="mailto:info@cakehome.vn">info@cakehome.vn</a></p>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5><i class="bi bi-clock text-primary-custom"></i> Giờ Làm Việc</h5>
                        <p class="mb-0">Thứ 2 - Chủ Nhật: 8:00 - 22:00</p>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5><i class="bi bi-chat-dots text-primary-custom"></i> Mạng Xã Hội</h5>
                        <div class="social-icons">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-youtube"></i></a>
                            <a href="#"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4 text-center">Vị Trí Cửa Hàng</h3>
                <div class="ratio ratio-21x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4326571760346!2d106.69972831411804!3d10.776889992320196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38f9ed887b%3A0x14aded5703768989!2zQsOgIFRodXkgxLDDoG5n!5e0!3m2!1svi!2s!4v1234567890" 
                            style="border:0; border-radius: 15px;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

