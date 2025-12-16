@extends('admin.layouts.master')

@section('title', 'Sửa Voucher')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Sửa Voucher</h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mã voucher *</label>
                            <input type="text" class="form-control" name="code"
                                value="{{ old('code', $voucher->code) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Loại giảm *</label>
                            <select class="form-select" name="discount_type" required>
                                <option value="percentage"
                                    {{ old('discount_type', $voucher->discount_type) === 'percentage' ? 'selected' : '' }}>
                                    Phần trăm</option>
                                <option value="fixed_amount"
                                    {{ old('discount_type', $voucher->discount_type) === 'fixed_amount' ? 'selected' : '' }}>
                                    Số tiền</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giá trị giảm *</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="discount_value"
                                value="{{ old('discount_value', $voucher->discount_value) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Đơn tối thiểu</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="min_order_value"
                                value="{{ old('min_order_value', $voucher->min_order_value) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giảm tối đa (áp dụng cho %)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="max_discount"
                                value="{{ old('max_discount', $voucher->max_discount) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giới hạn lượt dùng</label>
                            <input type="number" min="1" class="form-control" name="usage_limit"
                                value="{{ old('usage_limit', $voucher->usage_limit) }}">
                            <small class="text-muted">Đã dùng: {{ $voucher->used_count }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giới hạn / user</label>
                            <input type="number" min="1" class="form-control" name="used_per_user_limit"
                                value="{{ old('used_per_user_limit', $voucher->used_per_user_limit) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Hiệu lực từ</label>
                            <input type="datetime-local" class="form-control" name="valid_from"
                                value="{{ old('valid_from', $voucher->valid_from ? $voucher->valid_from->format('Y-m-d\\TH:i') : '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Hiệu lực đến</label>
                            <input type="datetime-local" class="form-control" name="valid_until"
                                value="{{ old('valid_until', $voucher->valid_until ? $voucher->valid_until->format('Y-m-d\\TH:i') : '') }}">
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label">Kích hoạt</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Lưu</button>
                            <a class="btn btn-outline-secondary" href="{{ route('admin.vouchers.index') }}">Quay lại</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
