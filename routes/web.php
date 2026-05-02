<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingHeroSlidesController;
use App\Http\Controllers\ShopAuthController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/shop', function () {
    return view('shop');
})->name('shop');

Route::post('/landing/hero-slides', [LandingHeroSlidesController::class, 'store'])
    ->name('landing.hero-slides.store');

Route::get('/vendor/sign-in', function () {
    return view('vendor-signin');
})->name('vendor.signin');

Route::post('/vendor/sign-in', [ShopAuthController::class, 'signIn'])->name('vendor.signin.submit');

Route::post('/shop/sign-in', [ShopAuthController::class, 'signIn'])->name('shop.signin');
Route::post('/shop/sign-up', [ShopAuthController::class, 'signUp'])->name('shop.signup');
Route::post('/shop/logout', [ShopAuthController::class, 'logout'])->name('shop.logout');
Route::get('/shop/products', [ShopAuthController::class, 'listProducts'])->name('shop.products.index');

Route::middleware('auth')->group(function () {
    Route::get('/shop/profile', [ShopAuthController::class, 'profile'])->name('shop.profile');
    Route::post('/shop/addresses', [ShopAuthController::class, 'addAddress'])->name('shop.addresses.store');
    Route::post('/shop/orders', [ShopAuthController::class, 'createOrder'])->name('shop.orders.store');
    Route::post('/shop/products', [ShopAuthController::class, 'addProduct'])->name('shop.products.store');
    Route::patch('/shop/products/{item}', [ShopAuthController::class, 'updateProduct'])->name('shop.products.update');
    Route::delete('/shop/products/{item}', [ShopAuthController::class, 'deleteProduct'])->name('shop.products.delete');
    Route::get('/shop/admin/orders', [ShopAuthController::class, 'adminOrders'])->name('shop.admin.orders');
    Route::patch('/shop/admin/orders/{order}', [ShopAuthController::class, 'updateOrderStatus'])->name('shop.admin.orders.update');
    Route::patch('/shop/orders/{order}/cancel', [ShopAuthController::class, 'cancelOrder'])->name('shop.orders.cancel');
    Route::patch('/shop/orders/{order}/receive', [ShopAuthController::class, 'receiveOrder'])->name('shop.orders.receive');
    Route::delete('/shop/orders/{order}', [ShopAuthController::class, 'deleteOrder'])->name('shop.orders.delete');
});
