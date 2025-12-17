<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAccountController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->whereIn('role_id', [1, 2])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $title = 'Quản lý nhân viên';

        return view('admin.pages.staff.index', compact('users', 'title'));
    }

    public function create()
    {
        $title = 'Tạo tài khoản nhân viên';
        return view('admin.pages.staff.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,staff'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'role.in' => 'Vai trò không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $roleId = Role::where('name', $request->input('role'))->value('id');
        if (!$roleId) {
            return back()->with('error', 'Không tìm thấy vai trò. Hãy chạy seed roles trước.');
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'status' => 'active',
            'role_id' => $roleId,
            'phone_number' => $request->input('phone_number'),
            'address' => $request->input('address'),
            'activation_token' => null,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Tạo tài khoản thành công!');
    }

    public function edit($id)
    {
        $user = User::with('role')
            ->whereIn('role_id', [1, 2])
            ->findOrFail($id);

        $title = 'Cập nhật tài khoản';

        return view('admin.pages.staff.edit', compact('user', 'title'));
    }

    public function update(Request $request, $id)
    {
        $user = User::whereIn('role_id', [1, 2])->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:admin,staff'],
            'status' => ['required', 'in:active,pending,banned'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'role.in' => 'Vai trò không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $roleId = Role::where('name', $request->input('role'))->value('id');
        if (!$roleId) {
            return back()->with('error', 'Không tìm thấy vai trò. Hãy chạy seed roles trước.');
        }

        $user->name = $request->input('name');
        $user->role_id = $roleId;
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('admin.staff.index')->with('success', 'Cập nhật tài khoản thành công!');
    }
}
