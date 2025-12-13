@extends('client.layouts.master')

@section('title', 'Đặt Lại Mật Khẩu - CakeHome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock fs-1 text-primary"></i>
                        <h3 class="mt-3">Đặt Lại Mật Khẩu</h3>
                        <p class="text-muted">Nhập mật khẩu mới của bạn</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="mb-3">  
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" placeholder="Nhập email của bạn" 
                                       value="{{ $email ?? old('email') }}" readonly>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật Khẩu Mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Nhập mật khẩu mới">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Xác Nhận Mật Khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-check-circle"></i> Đặt Lại Mật Khẩu
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.text-primary {
    color: var(--primary-color) !important;
}

.input-group-text {
    background-color: #f8f9fa;
}

.card {
    border-radius: 15px;
}
</style>
@endsection
