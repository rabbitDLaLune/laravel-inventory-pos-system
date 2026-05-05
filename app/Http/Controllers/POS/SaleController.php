<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\CartService;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function checkout(CartService $cartService)
    {
        if ($cartService->count() <= 0) {
            return redirect()
                ->route('pos.index')
                ->withErrors('Cart is empty.');
        }

        return view('pos.checkout', [
            'cartItems' => $cartService->all(),
            'subtotal' => $cartService->subtotal(),
            'cartCount' => $cartService->count(),
        ]);
    }

    public function processCheckout(Request $request, CartService $cartService, SaleService $saleService)
    {
        if ($cartService->count() <= 0) {
            return redirect()
                ->route('pos.index')
                ->withErrors('Cart is empty.');
        }

        $data = $request->validate([
            'payment_method' => ['required', 'in:cash,card,qr,ewallet'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
        ]);

        if ((float) $data['amount_paid'] < $cartService->subtotal()) {
            return back()
                ->withInput()
                ->withErrors('Amount paid is less than total amount.');
        }

        try {
            $sale = $saleService->checkout($cartService->all()->toArray(), $data);

            $cartService->clear();

            return redirect()
                ->route('sales.receipt', $sale)
                ->with('success', 'Sale completed successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['items', 'payments', 'user', 'warehouse']);

        return view('pos.receipt', compact('sale'));
    }
}
