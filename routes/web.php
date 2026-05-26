<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


// 1. Public Marketplace Catalog Routes
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/shop', [LandingPageController::class, 'shop'])->name('shop.index');
Route::get('/shop/product/{id}', [LandingPageController::class, 'show'])->name('shop.show');

// Static info pages
Route::get('/about', [LandingPageController::class, 'about'])->name('about');
Route::get('/contact', [LandingPageController::class, 'contact'])->name('contact');
Route::get('/faq', [LandingPageController::class, 'faq'])->name('faq');

// 2. Shopping Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    
    // Coupons
    Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');
    Route::get('/coupon/remove', [CartController::class, 'removeCoupon'])->name('coupon.remove');
});

// 3. Authenticated Secure Routes (Checkout, Dashboards)
Route::middleware(['auth'])->group(function () {
    
    // Wishlist AJax toggler
    Route::post('/wishlist/toggle/{productId}', [LandingPageController::class, 'toggleWishlist'])->name('wishlist.toggle');

    // Product Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Secure checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/invoice/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/track/{id}', [OrderController::class, 'track'])->name('orders.track');

    // Central Dashboard Redirect
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-restricted Farmer Panel
    Route::middleware(['role:farmer'])->group(function () {
        Route::post('/dashboard/profile', [FarmerDashboardController::class, 'updateProfile'])->name('dashboard.profile');
        Route::post('/dashboard/review', [FarmerDashboardController::class, 'submitReview'])->name('dashboard.review');
        Route::post('/dashboard/notifications/read', [FarmerDashboardController::class, 'markNotificationsRead'])->name('dashboard.notifications.read');
    });

    // Role-restricted Seller Panel (Only for approved status)
    Route::middleware(['role:seller'])->group(function () {
        Route::post('/dashboard/seller/product', [SellerDashboardController::class, 'storeProduct'])->name('dashboard.seller.product.store');
        Route::put('/dashboard/seller/product/{id}', [SellerDashboardController::class, 'updateProduct'])->name('dashboard.seller.product.update');
        Route::delete('/dashboard/seller/product/{id}', [SellerDashboardController::class, 'deleteProduct'])->name('dashboard.seller.product.delete');
        Route::post('/dashboard/seller/order/{id}/status', [SellerDashboardController::class, 'updateOrderStatus'])->name('dashboard.seller.order.status');
    });

    // Role-restricted Admin Command Console
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard/admin/users', [AdminDashboardController::class, 'users'])->name('dashboard.admin.users');
        Route::post('/dashboard/admin/seller/toggle/{id}', [AdminDashboardController::class, 'toggleSellerStatus'])->name('dashboard.admin.seller.toggle');
        Route::post('/dashboard/admin/category', [AdminDashboardController::class, 'storeCategory'])->name('dashboard.admin.category.store');
        Route::delete('/dashboard/admin/category/{id}', [AdminDashboardController::class, 'deleteCategory'])->name('dashboard.admin.category.delete');
        Route::delete('/dashboard/admin/product/{id}', [AdminDashboardController::class, 'deleteProduct'])->name('dashboard.admin.product.delete');
        Route::post('/dashboard/admin/order/{id}/status', [AdminDashboardController::class, 'updateOrderStatus'])->name('dashboard.admin.order.status');
    });

    // Profile (Standard Breeze placeholder edit)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
