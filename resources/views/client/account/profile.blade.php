@extends('client.layouts.master')

@section('title', 'Thông Tin Cá Nhân - CakeHome')

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
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Thông Tin Cá Nhân
                    </h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số Điện Thoại</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                       name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày Tham Gia</label>
                                <input type="text" class="form-control" 
                                       value="{{ $user->created_at->format('d/m/Y') }}" disabled>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('account.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Quay Lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
