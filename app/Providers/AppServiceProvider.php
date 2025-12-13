<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\CartItem;
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

        // Chia sẻ biến $categories cho header và footer
        View::composer(['client.partials.header'], function ($view) {
            $view->with('categories', Category::all());
            $view->with('wishlists',Wishlist::where('user_id',Auth::id())->get());
            
            // Cart count
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
            } else {
                $cartCount = count(session()->get('cart', []));
            }
            $view->with('cartCount', $cartCount);
        });
        
    }
}
