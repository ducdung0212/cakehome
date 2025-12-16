<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\CartItem; 
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
        $validated = $request->validated(); 
        $token = Str::random(60);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role_id' => 3,
            'activation_token' => $token
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));
        
        return redirect()->route('login')
            ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
    }
    
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => 'active'
        ])) {
            // Kiểm tra quyền (Role ID = 3 : Khách hàng)
            if(in_array(Auth::user()->role_id, [3])){
                $request->session()->regenerate();
                
                // --- [BẮT ĐẦU] LOGIC GỘP GIỎ HÀNG ---
                $sessionCart = session()->get('cart', []);

                if (!empty($sessionCart)) {
                    foreach ($sessionCart as $productId => $details) {
                        // Tìm xem trong Database user này đã có sản phẩm này chưa
                        $dbCart = CartItem::where('user_id', Auth::id())
                                      ->where('product_id', $productId)
                                      ->first();

                        if ($dbCart) {
                            // Nếu có rồi -> Cộng dồn số lượng
                            $dbCart->quantity += $details['quantity'];
                            $dbCart->save();
                        } else {
                            // Nếu chưa có -> Tạo mới
                            CartItem::create([
                                'user_id' => Auth::id(),
                                'product_id' => $productId,
                                'quantity' => $details['quantity']
                            ]);
                        }
                    }
                    // Xóa giỏ hàng Session sau khi đã chuyển vào DB
                    session()->forget('cart');
                }
                // --- [KẾT THÚC] LOGIC GỘP GIỎ HÀNG ---

                return redirect()->route('home')
                    ->with('success', 'Đăng nhập thành công! Chào mừng bạn trở lại.');
            } else {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Tài khoản không có quyền truy cập.');
            }
        }
        
        return redirect()->route('login')
            ->with('error', 'Thông tin đăng nhập không chính xác hoặc tài khoản chưa được kích hoạt.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Đăng xuất thành công!');
    }
    
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Token kích hoạt không hợp lệ.');
        }

        $user->activation_token = null;
        $user->status = 'active';
        $user->save();
        
        return redirect()->route('login')
            ->with('success', 'Kích hoạt tài khoản thành công!');
    }
}