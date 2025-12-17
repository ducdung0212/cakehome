@extends('admin.layouts.master')

@section('title', 'Tài khoản')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tài khoản</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Tài khoản</h1>
                <p class="text-muted mb-0">{{ $user->name }} ({{ $user->email }})</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Đổi mật khẩu</h5>

                <form method="POST" action="{{ route('admin.account.password.update') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6"></div>

                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-shield-lock me-1"></i> Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
