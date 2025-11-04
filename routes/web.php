<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AuthController;

// Client Routes
Route::get('/', function () {
    return view('client.pages.home');
})->name('home');

Route::get('/products', function () {
    return view('client.pages.products');
})->name('products');

Route::get('/products/{id}', function ($id) {
    return view('client.pages.product-detail');
})->name('product.detail');

Route::get('/cart', function () {
    return view('client.pages.cart');
})->name('cart');

Route::get('/wishlist', function () {
    return view('client.pages.wishlist');
})->name('wishlist');

Route::get('/checkout', function () {
    return view('client.pages.checkout');
})->name('checkout');

Route::get('/contact', function () {
    return view('client.pages.contact');
})->name('contact');

Route::get('/about', function () {
    return view('client.pages.about');
})->name('about');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

// Social Auth Routes (placeholder)
Route::get('/auth/google', function() {
    return redirect('/login')->with('info', 'Google login coming soon');
})->name('auth.google');

Route::get('/auth/facebook', function() {
    return redirect('/login')->with('info', 'Facebook login coming soon');
})->name('auth.facebook');
Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate.account');

// Terms & Privacy Routes (placeholder)
Route::get('/terms', function() {
    return view('client.pages.terms');
})->name('terms');

Route::get('/privacy', function() {
    return view('client.pages.privacy');
})->name('privacy');