<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;

class POSController extends Controller
{
    public function index(CartService $cartService)
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        return view('pos.index', [
            'products' => $products,
            'cartItems' => $cartService->all(),
            'subtotal' => $cartService->subtotal(),
            'cartCount' => $cartService->count(),
        ]);
    }
}
