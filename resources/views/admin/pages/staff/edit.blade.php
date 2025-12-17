@extends('admin.layouts.master')

@section('title', 'Cập nhật tài khoản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Nhân viên</a></li>
    <li class="breadcrumb-item active">Cập nhật</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Cập nhật tài khoản</h1>
                <p class="text-muted mb-0">{{ $user->email }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.staff.update', $user->id) }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Vai trò</label>
                        @php
                            $currentRole = optional($user->role)->name;
                        @endphp
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="admin" {{ old('role', $currentRole) === 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="staff" {{ old('role', $currentRole) === 'staff' ? 'selected' : '' }}>Nhân viên
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>active
                            </option>
                            <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>
                                pending</option>
                            <option value="banned" {{ old('status', $user->status) === 'banned' ? 'selected' : '' }}>banned
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
