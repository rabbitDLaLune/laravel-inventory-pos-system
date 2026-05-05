<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function checkout(array $cartItems, array $paymentData): Sale
    {
        return DB::transaction(function () use ($cartItems, $paymentData) {
            $subtotal = collect($cartItems)->sum('line_total');
            $amountPaid = (float) $paymentData['amount_paid'];
            $changeAmount = max($amountPaid - $subtotal, 0);

            $user = User::first();
            $warehouse = Warehouse::first();

            $sale = Sale::create([
                'user_id' => $user->id,
                'warehouse_id' => $warehouse->id,
                'invoice_number' => 'INV-' . now()->format('YmdHis'),
                'status' => 'completed',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $subtotal,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'customer_name' => $paymentData['customer_name'] ?? null,
                'customer_phone' => $paymentData['customer_phone'] ?? null,
                'completed_at' => now(),
            ]);

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock_qty < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => 0,
                    'tax_rate' => 0,
                    'total_price' => $item['line_total'],
                ]);

                $stockBefore = $product->stock_qty;
                $stockAfter = $stockBefore - $item['quantity'];

                $product->update([
                    'stock_qty' => $stockAfter,
                ]);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'warehouse_id' => $warehouse->id,
                    'user_id' => $user->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference' => $sale->invoice_number,
                    'reason' => 'sale',
                    'notes' => 'Stock deducted from POS sale.',
                ]);
            }

            Payment::create([
                'sale_id' => $sale->id,
                'method' => $paymentData['payment_method'],
                'amount' => $amountPaid,
                'reference' => $paymentData['payment_reference'] ?? null,
                'gateway' => $paymentData['payment_method'] === 'qr' ? 'Manual QR' : null,
                'status' => 'paid',
                'gateway_response' => null,
                'paid_at' => now(),
            ]);

            return $sale;
        });
    }
}
