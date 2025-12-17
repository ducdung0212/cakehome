<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function profile()
    {
        /** @var User $user */
        $user = Auth::guard('admin')->user();
        $title = 'Tài khoản';

        return view('admin.pages.account.profile', compact('user', 'title'));
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
