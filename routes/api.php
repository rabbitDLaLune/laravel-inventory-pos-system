<?php

use App\Http\Controllers\Api\ProductLookupController;
use Illuminate\Support\Facades\Route;

Route::get('/products/lookup', [ProductLookupController::class, 'lookup'])
    ->name('api.products.lookup');
