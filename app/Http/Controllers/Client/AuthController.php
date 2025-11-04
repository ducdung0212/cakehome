<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\ActivationMail;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }
    
    public function register(RegisterRequest $request)
    {
        // Dữ liệu đã được validate tự động
        $validated = $request->validated(); 
        $token=Str::random(60);
        // Tạo user mới
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role_id' => 3, // Customer role
            'activation_token'=>$token
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

        return redirect()->route('home')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
    }
    
    public function login(LoginRequest $request)
    {
        // Dữ liệu đã được validate tự động
        $validated = $request->validated();

        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => 'active'
        ])) {
            if(in_array(Auth::user()->role_id, [3])){
                $request->session()->regenerate();
                return redirect()->route('home');
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Chưa có làm xong.');
            }
        }
        return redirect()->route('login')->with('error', 'Thông tin đăng nhập không chính xác hoặc tài khoản chưa được kích hoạt.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->route('register')->with('error', 'Token kích hoạt không hợp lệ.');
        }

        $user->activation_token = null;
        $user->status = 'active';
        $user->save();
        return redirect()->route('login')->with('success', 'Kích hoạt tài khoản thành công!');
    }
}
