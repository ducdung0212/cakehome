<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
   public function showForgotPasswordForm()
    {
        return view('client.auth.forgot-password');
    }
    
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $status=Password::sendResetLink($request->only('email'));
        if($status===Password::RESET_LINK_SENT){
            return back()->with('success','Liên kết đặt lại mật khẩu đã được gửi đến email của bạn!');
        }
        return back()->with('error','Không thể gửi liên kết đặt lại mật khẩu đến email của bạn!');
    }
}
