<?php

use App\Http\Controllers\Api\ProductLookupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\POS\CartController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Product Lookup
|--------------------------------------------------------------------------
| Must be before Route::resource('products', ...)
*/
Route::get('/products/lookup', [ProductLookupController::class, 'lookup'])
    ->name('products.lookup');

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::get('/pos', [POSController::class, 'index'])
    ->name('pos.index');

Route::post('/pos/cart/add', [CartController::class, 'add'])
    ->name('pos.cart.add');

Route::post('/pos/cart/update', [CartController::class, 'update'])
    ->name('pos.cart.update');

Route::post('/pos/cart/remove', [CartController::class, 'remove'])
    ->name('pos.cart.remove');

Route::post('/pos/cart/clear', [CartController::class, 'clear'])
    ->name('pos.cart.clear');

Route::get('/pos/checkout', [SaleController::class, 'checkout'])
    ->name('pos.checkout');

Route::post('/pos/checkout', [SaleController::class, 'processCheckout'])
    ->name('pos.checkout.process');

Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
    ->name('sales.receipt');
