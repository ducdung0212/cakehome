@extends('admin.layouts.master')

@section('title', 'Quản Lý Danh Mục')

@section('breadcrumb')
    <li class="breadcrumb-item active">Danh Mục</li>
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

        <div class="row">
            <div class="col-lg-4">
                <!-- Add/Edit Category Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thêm Danh Mục Mới</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.add') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tên Danh Mục <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" readonly>
                                <div class="form-text">Để trống để tự động tạo</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô Tả</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hình Ảnh</label>
                                <input type="file" class="form-control" name="images" accept="image/*">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm Danh Mục
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Categories List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Danh Sách Danh Mục</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="25%">Hình Ảnh</th>
                                        <th width="35%">Tên Danh Mục</th>
                                        <th width="15%">Số Sản Phẩm</th>
                                        <th width="15%" class="text-end">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>
                                                <img src=" {{ asset('storage/' . $category->images) }} "
                                                    alt="{{ $category->name }}" class="img-thumbnail"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $category->name }}</div>
                                                <small class="text-muted">{{ $category->slug }}</small>
                                            </td>
                                            <td><span class="badge bg-info">{{ $category->products->count() }}</span></td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                        class="btn btn-outline-primary" title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.categories.delete') }}" method="POST"
                                                        style="display: inline;"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                                        @csrf
                                                        <input type="hidden" name="category_id"
                                                            value="{{ $category->id }}">
                                                        <button type="submit" class="btn btn-outline-danger"
                                                            title="Xóa">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
