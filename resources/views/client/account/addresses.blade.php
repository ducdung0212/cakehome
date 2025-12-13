@extends('client.layouts.master')

@section('title', 'Địa Chỉ - CakeHome')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            @include('client.account.partials.sidebar')

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        Địa Chỉ Giao Hàng
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="bi bi-plus-circle me-1"></i> Thêm Địa Chỉ
                    </button>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row g-3">
                    @forelse($shippingAddresses as $address)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0">{{ $address->full_name }}</h6>
                                        @if ($address->default)
                                            <span class="badge bg-primary">Mặc Định</span>
                                        @endif
                                    </div>

                                    <p class="text-muted mb-2">
                                        <i class="bi bi-telephone me-2"></i>{{ $address->phone_number }}
                                    </p>

                                    <p class="mb-3">
                                        <i class="bi bi-geo-alt me-2"></i>
                                        {{ $address->address }}
                                    </p>

                                    <div class="d-flex gap-2">

                                        <button class="btn btn-sm btn-outline-primary flex-fill" data-bs-toggle="modal"
                                            data-bs-target="#editAddressModal">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </button>


                                        <form action="{{ route('account.addresses.delete', $address->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Bạn có muốn xóa địa chỉ này không?')">
                                                <i class="bi bi-trash">Xóa</i>
                                            </button>
                                        </form>
                                        @if (!$address->default)
                                        <form action="{{ route('account.addresses.setDefault',$address->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check"></i> Đặt Mặc Định
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-geo-alt-fill text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 text-muted">Chưa có địa chỉ nào</h5>
                                <p class="text-muted">Thêm địa chỉ giao hàng để thanh toán nhanh hơn</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm Địa Chỉ Đầu Tiên
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Địa Chỉ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('account.addresses.add') }}" method="POST" id="addAddressForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ Tên Người Nhận</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa Chỉ</label>
                            <textarea class="form-control" rows="2" name="address" required></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="setDefault" name="default">
                            <label class="form-check-label" for="setDefault">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu Địa Chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('account.addresses.edit', $address->id) }}" method="POST"
                        id="editAddressForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Họ Tên Người Nhận</label>
                            <input type="text" class="form-control" name="full_name" required
                                value="{{ $address->full_name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" name="phone_number" required
                                value="{{ $address->phone_number }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa Chỉ</label>
                            <textarea class="form-control" rows="2" name="address" required>{{ $address->address }}</textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="setDefault" name="default"
                                {{ $address->default ? 'checked' : '' }}>
                            <label class="form-check-label" for="setDefault">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu Địa Chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
