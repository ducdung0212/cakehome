<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Đăng ký Observer
        Order::observe(OrderObserver::class);

        // Global site settings (DB-backed)
        $siteSettings = [];
        try {
            if (Schema::hasTable('settings')) {
                $siteSettings = Cache::rememberForever('settings.kv', function () {
                    return Setting::query()->pluck('value', 'key')->toArray();
                });
            }
        } catch (\Throwable $e) {
            $siteSettings = [];
        }

        config()->set('site_settings', $siteSettings);
        View::share('siteSettings', $siteSettings);

        // Chia sẻ biến $categories cho header và footer
        View::composer(['client.layouts.header'], function ($view) {
            $view->with('categories', Category::all());
            $view->with('wishlists', Wishlist::where('user_id', Auth::id())->get());

            // Cart count
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
            } else {
                $cartCount = count(session()->get('cart', []));
            }
            $view->with('cartCount', $cartCount);
        });
        View::composer(['admin.layouts.header'], function ($view) {
            $view->with('user', Auth::guard('admin')->user());
        });
    }
}
