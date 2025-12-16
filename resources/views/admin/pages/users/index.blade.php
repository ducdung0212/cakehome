@extends('admin.layouts.master')

@section('title', 'Quản Lý Khách Hàng')

@section('breadcrumb')
    <li class="breadcrumb-item active">Khách Hàng</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Quản Lý Khách Hàng</h1>
            </div>
        </div>

        <!-- Customer Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $users->count() }}</h3>
                        <p class="mb-0">Tổng Khách Hàng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ $users->where('status', 'active')->count() }}</h3>
                        <p class="mb-0">Đang Hoạt Động</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h3 class="text-warning">{{ $users->where('status', 'pending')->count() }}</h3>
                        <p class="mb-0">Chờ Kích Hoạt</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <h3 class="text-danger">{{ $users->where('status', 'banned')->count() }}</h3>
                        <p class="mb-0">Đã Chặn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="/admin/users" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tên, email, số điện thoại..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ kích hoạt
                                </option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Đã chặn
                                </option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Đã xóa
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="name_asc">Tên A-Z</option>
                                <option value="name_desc">Tên Z-A</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Tìm
                            </button>
                            <a href="/admin/users" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="customersTable">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="18%">Họ Tên</th>
                                <th width="20%">Email</th>
                                <th width="12%">Điện Thoại</th>
                                <th width="18%">Địa Chỉ</th>
                                <th width="10%">Trạng Thái</th>
                                <th width="10%">Ngày Đăng Ký</th>
                                <th width="7%" class="text-end">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number ?? '-' }}</td>
                                    <td>
                                        <span title="{{ $user->address }}">
                                            {{ Str::limit($user->address ?? '-', 20) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->status === 'active')
                                            <span class="badge bg-success">{{ $user->status_text }}</span>
                                        @elseif($user->status === 'pending')
                                            <span class="badge bg-warning">{{ $user->status_text }}</span>
                                        @elseif($user->status === 'banned')
                                            <span class="badge bg-danger">{{ $user->status_text }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $user->status_text }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info"
                                                title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if ($user->status === 'pending')
                                                <button type="button" class="btn btn-success btn-activate"
                                                    data-user-id="{{ $user->id }}"
                                                    data-url="{{ route('admin.users.activate') }}" title="Kích hoạt">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif

                                            @if ($user->status !== 'banned' && $user->status !== 'deleted')
                                                <button type="button" class="btn btn-warning btn-ban"
                                                    data-user-id="{{ $user->id }}" data-status="banned"
                                                    data-url="{{ route('admin.users.updateStatus', $user->id) }}"
                                                    title="Chặn">
                                                    <i class="bi bi-ban"></i>
                                                </button>
                                            @endif

                                            @if ($user->status === 'banned')
                                                <button type="button" class="btn btn-info btn-unban"
                                                    data-user-id="{{ $user->id }}" data-status="active"
                                                    data-url="{{ route('admin.users.updateStatus', $user->id) }}"
                                                    title="Bỏ chặn">
                                                    <i class="bi bi-unlock"></i>
                                                </button>
                                            @endif

                                            @if ($user->status !== 'deleted')
                                                <button type="button" class="btn btn-danger btn-delete"
                                                    data-user-id="{{ $user->id }}" data-status="deleted"
                                                    data-url="{{ route('admin.users.updateStatus', $user->id) }}"
                                                    title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-primary btn-restore"
                                                    data-user-id="{{ $user->id }}" data-status="active"
                                                    data-url="{{ route('admin.users.updateStatus', $user->id) }}"
                                                    title="Khôi phục">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Không tìm thấy khách hàng nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/users.js') }}"></script>
@endpush
