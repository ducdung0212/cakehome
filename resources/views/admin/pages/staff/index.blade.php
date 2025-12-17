@extends('admin.layouts.master')

@section('title', 'Quản Lý Nhân Viên')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nhân viên</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md">
                <h1 class="page-title mb-0">Quản Lý Nhân Viên</h1>
            </div>
            <div class="col-12 col-md-auto mt-3 mt-md-0">
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i> Thêm tài khoản
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table " >
                        <thead>
                            <tr>
                                <th width="8%">ID</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th width="15%">Vai trò</th>
                                <th width="15%">Trạng thái</th>
                                <th width="18%">Ngày tạo</th>
                                <th width="10%" class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td>{{ $u->id }}</td>
                                    <td class="fw-semibold">{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $u->role->name === 'admin' ? 'primary' : 'info' }}">
                                            {{ strtoupper($u->role->name) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $u->status === 'active' ? 'success' : 'warning' }}">
                                            {{ $u->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ optional($u->created_at)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('admin.staff.edit', $u->id) }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Chưa có tài khoản nhân viên nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation" class="mt-5">
                        {{ $users->appends(request()->query())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

