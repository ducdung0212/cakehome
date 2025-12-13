<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    
    public function showResetPasswordForm(Request $request, $token){
        return view('client.auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email // Thêm dòng này để lấy email từ URL
        ]);
    }
    
    public function resetPassword(ResetPasswordRequest $request){
       $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password){
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
       );
       
       if($status === Password::PASSWORD_RESET){
            return redirect()->route('login')->with('success','Mật khẩu đã được đặt lại thành công!');
       }
       
       return redirect()->route('login')->with('error','Đặt lại mật khẩu thất bại!');
    }
}