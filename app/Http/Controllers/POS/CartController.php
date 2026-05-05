<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if (! $product->is_active) {
            return back()->withErrors('Product is inactive.');
        }

        if ($product->stock_qty <= 0) {
            return back()->withErrors('Product is out of stock.');
        }

        $cartService->add($product, $data['quantity'] ?? 1);

        return redirect()
            ->route('pos.index')
            ->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cartService->update($data['product_id'], $data['quantity']);

        return redirect()
            ->route('pos.index')
            ->with('success', 'Cart updated.');
    }

    public function remove(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cartService->remove($data['product_id']);

        return redirect()
            ->route('pos.index')
            ->with('success', 'Item removed.');
    }

    public function clear(CartService $cartService)
    {
        $cartService->clear();

        return redirect()
            ->route('pos.index')
            ->with('success', 'Cart cleared.');
    }
}
