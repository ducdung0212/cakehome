@extends('client.layouts.master')

@section('title', 'Quên Mật Khẩu - CakeHome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-lock fs-1 text-primary"></i>
                        <h3 class="mt-3">Quên Mật Khẩu?</h3>
                        <p class="text-muted">Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
                    </div>

                    <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" placeholder="Nhập email của bạn" value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-send"></i> Gửi Liên Kết Đặt Lại
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
