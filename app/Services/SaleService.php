<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleService
{
    /**
     * Complete POS checkout.
     *
     * This method will:
     * - create sale record
     * - create sale item records
     * - deduct product stock
     * - create inventory movement records
     * - create payment record
     */
    public function checkout(array $cartItems, array $paymentData): Sale
    {
        if (empty($cartItems)) {
            throw new RuntimeException('Cart is empty.');
        }

        return DB::transaction(function () use ($cartItems, $paymentData) {
            // Calculate cart total
            $subtotal = (float) collect($cartItems)->sum('line_total');

            // Amount paid by customer
            $amountPaid = (float) $paymentData['amount_paid'];

            // Customer change
            $changeAmount = max($amountPaid - $subtotal, 0);

            /*
             * Get current cashier/admin user.
             *
             * For now:
             * - If login/authentication is available, use logged-in user.
             * - If not, use the first user from database as temporary fallback.
             */
            $user = Auth::user() ?? User::first();

            /*
             * Get default warehouse.
             *
             * For now, this system uses the first warehouse as default.
             * Later, you can allow admin/cashier to choose warehouse/branch.
             */
            $warehouse = Warehouse::first();

            if (! $user) {
                throw new RuntimeException('No user found. Please create or seed a user first.');
            }

            if (! $warehouse) {
                throw new RuntimeException('No warehouse found. Please create or seed a warehouse first.');
            }

            // Create main sale record
            $sale = Sale::create([
                'user_id' => $user->id,
                'warehouse_id' => $warehouse->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'status' => 'completed',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $subtotal,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'customer_name' => $paymentData['customer_name'] ?? null,
                'customer_phone' => $paymentData['customer_phone'] ?? null,
                'notes' => $paymentData['notes'] ?? null,
                'completed_at' => now(),
            ]);

            // Create sale items and deduct stock
            foreach ($cartItems as $item) {
                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $lineTotal = $quantity * $unitPrice;

                /*
                 * Lock product row during checkout.
                 *
                 * This prevents stock conflict if two sales happen
                 * at the same time for the same product.
                 */
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if (! $product->is_active) {
                    throw new RuntimeException("Product is inactive: {$product->name}");
                }

                if ($product->stock_qty < $quantity) {
                    throw new RuntimeException("Not enough stock for {$product->name}");
                }

                // Save product snapshot into sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => 0,
                    'tax_rate' => 0,
                    'total_price' => $lineTotal,
                ]);

                // Calculate stock before and after sale
                $stockBefore = (int) $product->stock_qty;
                $stockAfter = $stockBefore - $quantity;

                // Deduct stock from product
                $product->update([
                    'stock_qty' => $stockAfter,
                ]);

                // Save inventory movement record for audit/history
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'warehouse_id' => $warehouse->id,
                    'user_id' => $user->id,
                    'type' => 'out',
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference' => $sale->invoice_number,
                    'reason' => 'sale',
                    'notes' => 'Stock deducted from POS sale.',
                ]);
            }

            /*
             * Save payment record.
             *
             * amount = subtotal because that is the actual payment for the sale.
             * amount_paid and change_amount are stored in sales table.
             */
            Payment::create([
                'sale_id' => $sale->id,
                'method' => $paymentData['payment_method'],
                'amount' => $subtotal,
                'reference' => $paymentData['payment_reference'] ?? null,
                'gateway' => $paymentData['payment_method'] === 'qr' ? 'Manual QR' : null,
                'status' => 'paid',
                'gateway_response' => null,
                'paid_at' => now(),
            ]);

            return $sale;
        });
    }

    /**
     * Generate unique invoice number.
     *
     * Example:
     * INV-20260506153045-123
     */
    private function generateInvoiceNumber(): string
    {
        return 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    }
}
