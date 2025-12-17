@extends('admin.layouts.master')

@section('title', 'Cài đặt - Tổng quan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Cài đặt</li>
    <li class="breadcrumb-item active">Tổng quan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Cài đặt - Tổng quan</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="site_email"
                                value="{{ old('site_email', $settings['site_email'] ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="site_phone"
                                value="{{ old('site_phone', $settings['site_phone'] ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="site_address"
                                value="{{ old('site_address', $settings['site_address'] ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Google Maps (link nhúng iframe)</label>
                            <input type="url" class="form-control" name="site_google_map_embed_url"
                                value="{{ old('site_google_map_embed_url', $settings['site_google_map_embed_url'] ?? '') }}"
                                placeholder="Truy cập Google Map, chọn vị trí shop, chọn share và nhúng bản đồ, copy đoạn <iframe src...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giờ làm việc</label>
                            <input type="text" class="form-control" name="site_working_hours"
                                value="{{ old('site_working_hours', $settings['site_working_hours'] ?? '') }}"
                                placeholder="Ví dụ: 8:00 - 22:00 (Thứ 2 - Chủ Nhật)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hiển thị voucher trên client</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="client_show_vouchers" value="1"
                                    {{ old('client_show_vouchers', ($settings['client_show_vouchers'] ?? '1') === '1') ? 'checked' : '' }}>
                                <label class="form-check-label">Bật</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" class="form-control" name="site_facebook_url"
                                value="{{ old('site_facebook_url', $settings['site_facebook_url'] ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" class="form-control" name="site_instagram_url"
                                value="{{ old('site_instagram_url', $settings['site_instagram_url'] ?? '') }}">
                        </div>

                        <div class="col-12">
                            <hr>
                            <h5 class="mb-3">Trang chủ</h5>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Banner (tải ảnh lên)</label>

                            <input type="file" class="form-control" name="home_hero_background_image" accept="image/*"
                                id="banner_input" onchange="previewBanner(this)">

                            <div class="mt-2">
                                <small class="text-muted">Xem trước:</small>

                                @php
                                    $currentImage = null;
                                    if (!empty($settings['home_hero_background_path'])) {
                                        $currentImage = asset('storage/' . $settings['home_hero_background_path']);
                                    } elseif (!empty($settings['home_hero_background_url'])) {
                                        $currentImage = $settings['home_hero_background_url'];
                                    }
                                @endphp

                                <img id="banner_preview" src="{{ $currentImage }}" class="img-thumbnail"
                                    style="width: 250px; height: 160px; object-fit: cover; {{ $currentImage ? '' : 'display: none;' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề banner</label>
                            <input type="text" class="form-control" name="home_hero_title"
                                value="{{ old('home_hero_title', $settings['home_hero_title'] ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mô tả banner</label>
                            <input type="text" class="form-control" name="home_hero_subtitle"
                                value="{{ old('home_hero_subtitle', $settings['home_hero_subtitle'] ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Quảng cáo (dòng thông báo)</label>
                            <input type="text" class="form-control" name="home_announcement_text"
                                value="{{ old('home_announcement_text', $settings['home_announcement_text'] ?? '') }}"
                                placeholder="Ví dụ: Ưu đãi đặc biệt: ...">
                            <small class="text-muted">Để trống nếu không muốn hiển thị.</small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    function previewBanner(input) {
        var preview = document.getElementById('banner_preview');
        
        // Nếu người dùng có chọn file
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                // Gán đường dẫn ảnh mới vào src
                preview.src = e.target.result;
                // Đảm bảo ảnh được hiển thị (trường hợp trước đó chưa có ảnh)
                preview.style.display = 'block';
            }

            // Đọc file dưới dạng URL
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
