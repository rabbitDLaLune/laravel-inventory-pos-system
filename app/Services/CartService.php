<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private string $sessionKey = 'pos_cart';

    public function all(): Collection
    {
        return collect(session($this->sessionKey, []));
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->all();

        $key = (string) $product->id;

        $existing = $cart->get($key);

        if ($existing) {
            $existing['quantity'] += $quantity;
            $existing['line_total'] = $existing['quantity'] * $existing['unit_price'];
            $cart->put($key, $existing);
        } else {
            $cart->put($key, [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'quantity' => $quantity,
                'unit_price' => (float) $product->selling_price,
                'stock_qty' => $product->stock_qty,
                'line_total' => $quantity * (float) $product->selling_price,
            ]);
        }

        session([$this->sessionKey => $cart->toArray()]);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->all();

        $key = (string) $productId;

        if (! $cart->has($key)) {
            return;
        }

        if ($quantity <= 0) {
            $cart->forget($key);
        } else {
            $item = $cart->get($key);
            $item['quantity'] = $quantity;
            $item['line_total'] = $quantity * $item['unit_price'];
            $cart->put($key, $item);
        }

        session([$this->sessionKey => $cart->toArray()]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        $cart->forget((string) $productId);

        session([$this->sessionKey => $cart->toArray()]);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function subtotal(): float
    {
        return (float) $this->all()->sum('line_total');
    }

    public function count(): int
    {
        return (int) $this->all()->sum('quantity');
    }
}
