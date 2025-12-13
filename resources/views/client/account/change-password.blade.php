@extends('client.layouts.master')

@section('title', 'Đổi Mật Khẩu - CakeHome')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        @include('client.account.partials.sidebar')

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4">
                        <i class="bi bi-shield-lock text-primary me-2"></i>
                        Đổi Mật Khẩu
                    </h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('account.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Mật Khẩu Hiện Tại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password" required>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Mật Khẩu Mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Xác Nhận Mật Khẩu Mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       name="password_confirmation" required>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Sau khi đổi mật khẩu, bạn sẽ cần đăng nhập lại với mật khẩu mới.
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('account.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Quay Lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Đổi Mật Khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
