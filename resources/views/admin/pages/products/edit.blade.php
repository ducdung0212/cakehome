@extends('admin.layouts.master')

@section('title', 'Sửa Sản Phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản Phẩm</a></li>
    <li class="breadcrumb-item active">Sửa Sản Phẩm</li>
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

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $product->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" value="{{ $product->slug }}" readonly>
                                <div class="form-text">Tự động tạo từ tên sản phẩm</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea class="form-control" name="description" rows="8">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Hình Ảnh</h5>
                            <span class="badge bg-info">{{ $product->images->count() }} ảnh</span>
                        </div>
                        <div class="card-body">
                            @if ($product->images->count() > 0)
                                <div class="row g-3 mb-3" id="currentImages">
                                    @foreach ($product->images as $image)
                                        <div class="col-md-3" id="image-{{ $image->id }}">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image->image) }}"
                                                    class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 btn-delete-image"
                                                    data-image-id="{{ $image->id }}"
                                                    onclick="if(confirm('Bạn có chắc chắn muốn xóa ảnh này?')) { deleteImage({{ $image->id }}) }">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">Chưa có hình ảnh nào</div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Thêm Hình Ảnh Mới</label>
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
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="in_stock"
                                        {{ old('status', $product->status) == 'in_stock' ? 'selected' : '' }}>Còn hàng
                                    </option>
                                    <option value="out_of_stock"
                                        {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Hết hàng
                                    </option>
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
                                    value="{{ old('price', $product->price) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá Khuyến Mãi (₫)</label>
                                <input type="number" class="form-control" name="sale_price" min="0"
                                    value="{{ old('sale_price', $product->sale_price) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số Lượng Trong Kho <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" min="0"
                                    value="{{ old('stock', $product->stock) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Đơn Vị Tính <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="unit"
                                    value="{{ old('unit', $product->unit) }}" required>
                                <div class="form-text">Ví dụ: cái, hộp, set, kg...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Cập Nhật Sản Phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Quay Lại
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteImage(imageId) {
            fetch(`/admin/products/delete-image/${imageId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`image-${imageId}`).remove();

                        // Cập nhật badge count
                        const remainingImages = document.querySelectorAll('#currentImages .col-md-3').length;
                        document.querySelector('.badge.bg-info').textContent = remainingImages + ' ảnh';

                        // Hiển thị thông báo
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message);
                        } else {
                            alert(data.message);
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi xóa hình ảnh');
                });
        }
    </script>
@endpush
