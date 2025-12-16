@extends('admin.layouts.master')

@section('title', 'Quản Lý Sản Phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item active">Sản Phẩm</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="page-title">Quản Lý Sản Phẩm</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Thêm Sản Phẩm
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.products.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category_id" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng
                                </option>
                                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết
                                    hàng</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="10%">Hình Ảnh</th>
                                <th width="20%">Tên Sản Phẩm</th>
                                <th width="15%">Danh Mục</th>
                                <th width="12%">Giá</th>
                                <th width="10%">Số lượng</th>
                                <th width="10%">Trạng Thái</th>
                                <th width="8%" class="text-end">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if ($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image) }}"
                                                alt="{{ $product->name }}" class="img-thumbnail"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/60" alt="No image" class="img-thumbnail"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->slug }}</small>
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="fw-bold">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                                        @if ($product->sale_price)
                                            <small
                                                class="text-danger">{{ number_format($product->sale_price, 0, ',', '.') }}₫</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $product->stock }}/{{ $product->unit }}</span>
                                    </td>
                                    <td>
                                        @if ($product->status === 'in_stock')
                                            <span class="badge bg-success">Còn hàng</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-outline-primary" title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy') }}" method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Chưa có sản phẩm nào</p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    <nav aria-label="Page navigation" class="mt-5">
                        {{ $products->appends(request()->query())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
