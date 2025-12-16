@extends('admin.layouts.master')

@section('title', 'Quản Lý Voucher')

@section('breadcrumb')
    <li class="breadcrumb-item active">Voucher</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="page-title">Quản Lý Voucher</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Thêm Voucher
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Loại</th>
                                <th>Giá trị</th>
                                <th>Đơn tối thiểu</th>
                                <th>Giảm tối đa</th>
                                <th>Lượt dùng</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vouchers as $voucher)
                                <tr>
                                    <td class="fw-bold">{{ $voucher->code }}</td>
                                    <td>
                                        {{ $voucher->discount_type === 'percentage' ? 'Phần trăm' : 'Số tiền' }}
                                    </td>
                                    <td>
                                        @if ($voucher->discount_type === 'percentage')
                                            {{ rtrim(rtrim(number_format($voucher->discount_value, 2, '.', ''), '0'), '.') }}%
                                        @else
                                            {{ number_format($voucher->discount_value, 0, ',', '.') }}đ
                                        @endif
                                    </td>
                                    <td>{{ number_format($voucher->min_order_value, 0, ',', '.') }}đ</td>
                                    <td>{{ $voucher->max_discount ? number_format($voucher->max_discount, 0, ',', '.') . 'đ' : '-' }}
                                    </td>
                                    <td>
                                        {{ $voucher->used_count }}
                                        @if (!is_null($voucher->usage_limit))
                                            / {{ $voucher->usage_limit }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($voucher->is_active)
                                            <span class="badge bg-success">Đang bật</span>
                                        @else
                                            <span class="badge bg-secondary">Đang tắt</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('admin.vouchers.edit', $voucher->id) }}">
                                            Sửa
                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy') }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Xóa voucher này?');">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $voucher->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Chưa có voucher</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($vouchers->hasPages())
                <div class="card-footer">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
