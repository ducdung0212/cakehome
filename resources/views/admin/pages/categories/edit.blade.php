@extends('admin.layouts.master')

@section('title', 'Sửa Danh Mục')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh Mục</a></li>
    <li class="breadcrumb-item active">Sửa Danh Mục</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sửa Danh Mục: {{ $category->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Tên Danh Mục <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $category->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug"
                                    value="{{ old('slug', $category->slug) }}" readonly>
                                <div class="form-text">Tự động tạo từ tên danh mục</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea class="form-control" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hình Ảnh</label>

                                @if ($category->images)
                                    <div class="mb-2" id="currentImageContainer">
                                        <img src="{{ asset('storage/' . $category->images) }}" alt="{{ $category->name }}"
                                            class="img-thumbnail" style="max-width: 200px; height: auto;">
                                        <div class="form-text">Hình ảnh hiện tại</div>
                                    </div>
                                @endif

                                <input type="file" class="form-control" name="images" id="imageInput" accept="image/*">
                                <div class="form-text">Chọn file mới để thay thế hình ảnh hiện tại</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Quay Lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Cập Nhật Danh Mục
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('imageInput').addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const container = document.getElementById('currentImageContainer');
                if (container) {
                    container.style.display = 'none';
                }
            }
        });
    </script>
@endpush
