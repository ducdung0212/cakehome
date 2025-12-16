<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware(['auth.custom'])->group(function () {
        //DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //SETTINGS
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/general', [SettingController::class, 'general'])->name('general');
            Route::post('/general', [SettingController::class, 'updateGeneral'])->name('general.update');
            Route::get('/shipping', [SettingController::class, 'shipping'])->name('shipping');
            Route::post('/shipping', [SettingController::class, 'updateShipping'])->name('shipping.update');
        });

        //LOGOUT
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        //USERS - Customer Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::post('/activate', [UserController::class, 'activate'])->name('activate');
            Route::put('/{id}/status', [UserController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        //ORDERS
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        });

        //REVIEWS
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            Route::post('/{id}/approve', [ReviewController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [ReviewController::class, 'reject'])->name('reject');
            Route::post('/delete', [ReviewController::class, 'destroy'])->name('destroy');
        });

        //CONTACTS
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/{id}', [ContactController::class, 'show'])->name('show');
            Route::put('/{id}/status', [ContactController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
        });

        //NOTIFICATIONS
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::get('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
        });

        //VOUCHERS
        Route::prefix('vouchers')->name('vouchers.')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('index');
            Route::get('/create', [VoucherController::class, 'create'])->name('create');
            Route::post('/', [VoucherController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [VoucherController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [VoucherController::class, 'update'])->name('update');
            Route::post('/delete', [VoucherController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['permission'])->group(function () {
        //PRODUCTS
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [ProductController::class, 'update'])->name('update');
            Route::post('/delete', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/delete-image/{id}', [ProductController::class, 'deleteImage'])->name('deleteImage');
        });

        //CATEGORIES
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/add', [CategoryController::class, 'add'])->name('add');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [CategoryController::class, 'update'])->name('update');
            Route::post('/delete', [CategoryController::class, 'delete'])->name('delete');
        });
    });
});
