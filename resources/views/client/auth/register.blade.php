@extends('client.layouts.master')

@section('title', 'Đăng Ký - CakeHome')
 <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/favicon.png') }}">
@section('content')
<section class="py-5 bg-light-custom">
    <div class="container">
        <div class="auth-card">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Tạo Tài Khoản Mới</h3>
                    
                    <form action="{{ route('register.post') }}" method="POST" id="registerForm" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" placeholder="Nhập họ và tên"{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" placeholder="your@email.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   name="phone_number" placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" placeholder="">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" 
                                   name="password_confirmation" placeholder="" required>
                        </div>                   
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                            <i class="bi bi-person-plus"></i> Đăng Ký
                        </button>
                    </form>
                    
                    <div class="text-center my-3">
                        <span class="text-muted">Hoặc đăng ký với</span>
                    </div>
                    
                    <!-- Social Register -->
                    <a href="{{ route('auth.google') }}" class="btn w-100 social-login-btn">
                        <i class="bi bi-google text-danger"></i> Google
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="btn w-100 social-login-btn">
                        <i class="bi bi-facebook text-primary"></i> Facebook
                    </a>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary-custom fw-bold">Đăng nhập ngay</a></p>
                    </div>
                </div>
            </div>
            
            <!-- Benefits -->
            <div class="row g-3 mt-4">
                <div class="col-md-4 text-center">
                    <i class="bi bi-gift text-primary-custom fs-2"></i>
                    <p class="mb-0 mt-2"><strong>Ưu đãi đặc biệt</strong></p>
                    <small class="text-muted">Cho thành viên mới</small>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-bookmark-star text-primary-custom fs-2"></i>
                    <p class="mb-0 mt-2"><strong>Tích điểm thưởng</strong></p>
                    <small class="text-muted">Mua càng nhiều, lợi càng lớn</small>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-bell text-primary-custom fs-2"></i>
                    <p class="mb-0 mt-2"><strong>Thông báo khuyến mãi</strong></p>
                    <small class="text-muted">Nhận ưu đãi đầu tiên</small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
