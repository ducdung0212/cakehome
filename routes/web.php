<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ResetPasswordController;
use App\Http\Controllers\Client\ForgotPasswordController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\WishListController;
use App\Http\Controllers\Client\CartItemController;

// Client Routes

//HOME
Route::get('/',[HomeController::class,'index'])->name('home');
//PRODUCT
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}',[ProductController::class,'showDetail'])->name('product.detail');

//CONTACT
Route::get('/contact', function () {
    return view('client.pages.contact');
})->name('contact');
//ABOUT
Route::get('/about', function () {
    return view('client.pages.about');
})->name('about');
//CART
Route::get('/cart',[CartItemController::class,'index'])->name('cart.index');
Route::post('/cart/add', [CartItemController::class, 'addItemToCart'])->name('cart.add');
Route::post('/cart/update', [CartItemController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove', [CartItemController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartItemController::class, 'clearCart'])->name('cart.clear');    

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});
Route::middleware('auth.custom')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //WISHLIST
    Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product_id}', [WishListController::class, 'toggle'])->name('wishlist.toggle');

  

    Route::prefix('account')->name('account.')->group(function () {
        //PROFILE
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        //ADDRESS
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [AccountController::class, 'addAddress'])->name('addresses.add');
        Route::put('/addresses/{id}', [AccountController::class, 'editAddress'])->name('addresses.edit');
        Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('addresses.delete');
        Route::put('/addresses/{id}/set-default', [AccountController::class, 'setDefaultAddress'])->name('addresses.setDefault');
        //PASSWORD
        Route::get('/change-password', [AccountController::class, 'changePassword'])->name('change-password');
        Route::put('/change-password', [AccountController::class, 'updatePassword'])->name('password.update');
        //ORDER
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    });
});

Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate.account');



// Social Auth Routes (placeholder)
Route::get('/auth/google', function () {
    return redirect('/login')->with('info', 'Google login coming soon');
})->name('auth.google');

Route::get('/auth/facebook', function () {
    return redirect('/login')->with('info', 'Facebook login coming soon');
})->name('auth.facebook');
