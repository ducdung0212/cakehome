<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectGoogle()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        try {
            $socialUser = Socialite::driver('google')->user();

            $email = $socialUser->getEmail();
            $googleId = $socialUser->getId();

            $user = null;
            if (!empty($email)) {
                $user = User::where('email', $email)->first();
            }
            if (!$user && !empty($googleId)) {
                $user = User::where('google_id', $googleId)->first();
            }

            if (!$user) {
                if (empty($email)) {
                    return redirect()->route('login')->with('error', 'Google không cung cấp email. Vui lòng thử phương thức khác.');
                }

                $user = User::create([
                    'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User'),
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'status' => 'active',
                    'role_id' => 3,
                    'activation_token' => null,
                    'google_id' => $googleId,
                ]);
            } else {
                $updates = [
                    'google_id' => $googleId,
                ];

                if ($user->status !== 'active') {
                    $updates['status'] = 'active';
                    $updates['activation_token'] = null;
                }

                $user->update($updates);
            }

            Auth::login($user);
            $request->session()->regenerate();

            $this->mergeSessionCartIntoDb();

            return redirect()->route('home')->with('success', 'Đăng nhập Google thành công!');
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập Google. Vui lòng thử lại.');
        }
    }

    public function redirectFacebook()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        /** @var \Laravel\Socialite\Two\AbstractProvider $provider */
        $provider = Socialite::driver('facebook');

        return $provider->scopes(['email'])->redirect();
    }

    public function callbackFacebook(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        try {
            $socialUser = Socialite::driver('facebook')->user();

            $email = $socialUser->getEmail();
            $facebookId = $socialUser->getId();

            $user = null;
            if (!empty($email)) {
                $user = User::where('email', $email)->first();
            }
            if (!$user && !empty($facebookId)) {
                $user = User::where('facebook_id', $facebookId)->first();
            }

            if (!$user) {
                if (empty($email)) {
                    return redirect()->route('login')->with('error', 'Facebook không cung cấp email. Hãy cấp quyền email hoặc dùng cách đăng nhập khác.');
                }

                $user = User::create([
                    'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User'),
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'status' => 'active',
                    'role_id' => 3,
                    'activation_token' => null,
                    'facebook_id' => $facebookId,
                ]);
            } else {
                $updates = [
                    'facebook_id' => $facebookId,
                ];

                if ($user->status !== 'active') {
                    $updates['status'] = 'active';
                    $updates['activation_token'] = null;
                }

                $user->update($updates);
            }

            Auth::login($user);
            $request->session()->regenerate();

            $this->mergeSessionCartIntoDb();

            return redirect()->route('home')->with('success', 'Đăng nhập Facebook thành công!');
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập Facebook. Vui lòng thử lại.');
        }
    }

    private function mergeSessionCartIntoDb(): void
    {
        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart) || !Auth::check()) {
            return;
        }

        foreach ($sessionCart as $productId => $details) {
            $dbCart = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($dbCart) {
                $dbCart->quantity += (int) ($details['quantity'] ?? 0);
                $dbCart->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => (int) ($details['quantity'] ?? 0),
                ]);
            }
        }

        session()->forget('cart');
    }
}
