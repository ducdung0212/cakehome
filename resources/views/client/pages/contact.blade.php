@extends('client.layouts.master')

@section('title', 'Liên Hệ - CakeHome')

@section('content')
    @php
        $siteAddress = $siteSettings['site_address'] ?? '123 Đường ABC, Quận 1, TP. Hồ Chí Minh';
        $sitePhone = $siteSettings['site_phone'] ?? '1900-xxxx';
        $siteEmail = $siteSettings['site_email'] ?? 'info@cakehome.vn';
        $workHours = $siteSettings['site_working_hours'] ?? 'Thứ 2 - Chủ Nhật: 8:00 - 22:00';
        $fbUrl = $siteSettings['site_facebook_url'] ?? '#';
        $igUrl = $siteSettings['site_instagram_url'] ?? '#';
    @endphp
    <nav aria-label="breadcrumb" class="bg-light py-3">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Liên hệ</li>
            </ol>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-lg-5 col-md-6">
                    <h2 class="mb-4">Thông Tin Liên Hệ</h2>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="bi bi-geo-alt text-primary-custom"></i> Địa Chỉ</h5>
                            <p class="mb-0">{{ $siteAddress }}</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="bi bi-telephone text-primary-custom"></i> Điện Thoại</h5>
                            <p class="mb-0">Hotline: <a
                                    href="tel:{{ preg_replace('/\s+/', '', $sitePhone) }}">{{ $sitePhone }}</a></p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="bi bi-envelope text-primary-custom"></i> Email</h5>
                            <p class="mb-0"><a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a></p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="bi bi-clock text-primary-custom"></i> Giờ Làm Việc</h5>
                            <p class="mb-0">{{ $workHours }}</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5><i class="bi bi-chat-dots text-primary-custom"></i> Mạng Xã Hội</h5>
                            <div class="social-icons">
                                <a href="{{ $fbUrl }}" class="me-2"><i class="bi bi-facebook fs-5"></i></a>
                                <a href="{{ $igUrl }}" class="me-2"><i class="bi bi-instagram fs-5"></i></a>
                                <a href="#" class="me-2"><i class="bi bi-youtube fs-5"></i></a>
                                <a href="#"><i class="bi bi-tiktok fs-5"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <h3 class="mb-4">Vị Trí Cửa Hàng</h3>
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-2 h-100">
                            <div class="ratio ratio-0.39x1 h-100" style="min-height: 400px;">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1810.0177753560947!2d106.683930448508!3d10.729420935910417!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2s!5e0!3m2!1svi!2s!4v1765732926826!5m2!1svi!2s"
                                    style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
