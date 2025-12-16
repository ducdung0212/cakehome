@extends('admin.layouts.master')

@section('title', 'Thêm Sản Phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản Phẩm</a></li>
    <li class="breadcrumb-item active">Thêm Mới</li>
@endsection

@section('content')
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <!-- Basic Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thông Tin Cơ Bản</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea class="form-control" name="description" rows="8">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Hình Ảnh</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Hình Ảnh Sản Phẩm</label>
                                <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                <div class="form-text">Có thể chọn nhiều hình (tối đa 5 hình), kích thước tối đa 2MB/hình
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Status & Category -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Phân Loại</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Danh Mục <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="in_stock" {{ old('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng
                                    </option>
                                    <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Hết
                                        hàng</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Giá & Kho</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Giá Gốc (₫) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" min="0"
                                    value="{{ old('price') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá Khuyến Mãi (₫)</label>
                                <input type="number" class="form-control" name="sale_price" min="0"
                                    value="{{ old('sale_price') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số Lượng Trong Kho <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" min="0"
                                    value="{{ old('stock', 0) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Đơn Vị Tính <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="unit" value="{{ old('unit', 'cái') }}"
                                    required>
                                <div class="form-text">Ví dụ: cái, hộp, set, kg...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Lưu Sản Phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Hủy
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
