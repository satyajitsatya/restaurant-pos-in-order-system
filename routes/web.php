<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Menu Routes (Customer facing)
Route::get('/', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.home');
Route::get('/menu/product/{product}', [MenuController::class, 'show'])->name('menu.product.show');
Route::post('/menu/place-order', [MenuController::class, 'placeOrder'])->name('menu.place-order');
Route::get('/order/{order}/status', [MenuController::class, 'orderStatus'])->name('order.status');

// Customer order tracking routes
Route::get('/track-order/{orderId?}', [MenuController::class, 'trackOrder'])->name('menu.track-order');
Route::get('/api/order/{orderId}/status', [MenuController::class, 'getOrderStatus'])->name('menu.order-status');

// Authentication Routes
Auth::routes(['register' => false]); // Disable registration for security

// Admin Routes (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {

    // Admin Dashboard
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/live-data', [AdminController::class, 'getLiveData'])->name('live-data');
        Route::get('/orders/refresh', [AdminController::class, 'refreshRecentOrders'])->name('orders.refresh');

        // Orders Management
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/orders/pending/data', [OrderController::class, 'getPendingOrders'])->name('orders.pending');
        Route::post('/orderItem/{orderItem}/cancel', [OrderController::class, 'cancelItem'])->name('orderItem.cancel');


        // Categories Management  
        Route::resource('categories', CategoryController::class);

        // Products Management
        Route::resource('products', ProductController::class);
    });

    // Kitchen Routes
    Route::prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('index');
        Route::get('/live-data', [KitchenController::class, 'getLiveData'])->name('live-data');
        Route::post('/items/{orderItem}/status', [KitchenController::class, 'updateItemStatus'])->name('items.update-status');
        Route::post('/orders/{order}/ready', [KitchenController::class, 'markOrderReady'])->name('orders.ready');
    });
});

// Redirect after login based on user role
Route::get('/home', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->role === 'kitchen') {
        return redirect()->route('kitchen.index');
    }

    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
