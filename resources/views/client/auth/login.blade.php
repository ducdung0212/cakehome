@extends('client.layouts.master')

@section('title', 'Đăng Nhập - CakeHome')
 <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/favicon.png') }}">
@section('content')
<section class="py-5 bg-light-custom">
    <div class="container">
        <div class="auth-card">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Chào Mừng Trở Lại!</h3>
                    
                    <form id="loginForm" action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" placeholder="" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   name="password" placeholder="">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-decoration-none">Quên mật khẩu?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng Nhập
                        </button>
                    </form>
                    
                    <div class="text-center my-3">
                        <span class="text-muted">Hoặc đăng nhập với</span>
                    </div>
                    
                    <!-- Social Login -->
                    <a href="{{ route('auth.google') }}" class="btn w-100 social-login-btn">
                        <i class="bi bi-google text-danger"></i> Google
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="btn w-100 social-login-btn">
                        <i class="bi bi-facebook text-primary"></i> Facebook
                    </a>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-primary-custom fw-bold">Đăng ký ngay</a></p>
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
