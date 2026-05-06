<?php

use App\Http\Controllers\Api\ProductLookupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\StockMovementController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\POS\CartController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\Purchase\PurchaseOrderController;
use App\Http\Controllers\Purchase\SupplierController;
use App\Http\Controllers\Report\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Admin Management
|--------------------------------------------------------------------------
*/
Route::resource('users', UserController::class)
    ->middleware('role:admin');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected System Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,manager')
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Product Lookup
    |--------------------------------------------------------------------------
    | Must be before Route::resource('products', ...)
    */
    Route::get('/products/lookup', [ProductLookupController::class, 'lookup'])
        ->name('products.lookup');

    /*
    |--------------------------------------------------------------------------
    | POS and Checkout
    |--------------------------------------------------------------------------
    */
    Route::get('/pos', [POSController::class, 'index'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.index');

    Route::post('/pos/cart/add', [CartController::class, 'add'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.cart.add');

    Route::post('/pos/cart/update', [CartController::class, 'update'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.cart.update');

    Route::post('/pos/cart/remove', [CartController::class, 'remove'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.cart.remove');

    Route::post('/pos/cart/clear', [CartController::class, 'clear'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.cart.clear');

    Route::get('/pos/checkout', [SaleController::class, 'checkout'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.checkout');

    Route::post('/pos/checkout', [SaleController::class, 'processCheckout'])
        ->middleware('role:admin,manager,cashier')
        ->name('pos.checkout.process');

    /*
    |--------------------------------------------------------------------------
    | Sales
    |--------------------------------------------------------------------------
    */
    Route::get('/sales', [SaleController::class, 'index'])
        ->middleware('role:admin,manager,cashier')
        ->name('sales.index');

    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->middleware('role:admin,manager,cashier')
        ->name('sales.receipt');

    Route::get('/sales/{sale}', [SaleController::class, 'show'])
        ->middleware('role:admin,manager,cashier')
        ->name('sales.show');

    /*
    |--------------------------------------------------------------------------
    | Inventory Management
    |--------------------------------------------------------------------------
    */
    Route::resource('products', ProductController::class)
        ->middleware('role:admin,manager');

    Route::resource('categories', CategoryController::class)
        ->middleware('role:admin,manager');

    Route::resource('warehouses', WarehouseController::class)
        ->middleware('role:admin,manager');

    Route::get('/stock-movements', [StockMovementController::class, 'index'])
        ->middleware('role:admin,manager')
        ->name('stock-movements.index');

    Route::get('/stock-adjustments/create', [StockMovementController::class, 'create'])
        ->middleware('role:admin,manager')
        ->name('stock-adjustments.create');

    Route::post('/stock-adjustments', [StockMovementController::class, 'store'])
        ->middleware('role:admin,manager')
        ->name('stock-adjustments.store');

    /*
    |--------------------------------------------------------------------------
    | Purchase Management
    |--------------------------------------------------------------------------
    */
    Route::resource('suppliers', SupplierController::class)
        ->middleware('role:admin,manager');

    Route::resource('purchase-orders', PurchaseOrderController::class)
        ->middleware('role:admin,manager');

    Route::post('/purchase-orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receive'])
        ->middleware('role:admin,manager')
        ->name('purchase-orders.receive');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('role:admin,manager')
        ->name('reports.index');
});
