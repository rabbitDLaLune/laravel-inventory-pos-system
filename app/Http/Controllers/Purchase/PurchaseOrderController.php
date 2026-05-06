<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PurchaseOrderController extends Controller
{
    /**
     * Display purchase order list.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'warehouse', 'user'])
            ->latest()
            ->paginate(10);

        return view('purchase.orders.index', compact('purchaseOrders'));
    }

    /**
     * Show create purchase order form.
     */
    public function create()
    {
        return view('purchase.orders.create', [
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store purchase order with multiple items.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'expected_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity_ordered' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                $user = Auth::user() ?? User::first();

                if (! $user) {
                    throw new RuntimeException('No user found. Please create or seed a user first.');
                }

                $totalAmount = collect($data['items'])->sum(function ($item) {
                    return (int) $item['quantity_ordered'] * (float) $item['unit_cost'];
                });

                $purchaseOrder = PurchaseOrder::create([
                    'supplier_id' => $data['supplier_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'user_id' => $user->id,
                    'po_number' => $this->generatePoNumber(),
                    'status' => 'ordered',
                    'total_amount' => $totalAmount,
                    'ordered_at' => now()->toDateString(),
                    'expected_at' => $data['expected_at'] ?? null,
                    'received_at' => null,
                    'notes' => $data['notes'] ?? null,
                ]);

                foreach ($data['items'] as $item) {
                    $quantity = (int) $item['quantity_ordered'];
                    $unitCost = (float) $item['unit_cost'];
                    $totalCost = $quantity * $unitCost;

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => null,
                        'quantity_ordered' => $quantity,
                        'quantity_received' => 0,
                        'unit_cost' => $unitCost,
                        'total_cost' => $totalCost,
                    ]);
                }
            });

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Purchase order created successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Display purchase order detail.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load([
            'supplier',
            'warehouse',
            'user',
            'items.product',
        ]);

        return view('purchase.orders.show', compact('purchaseOrder'));
    }

    /**
     * Show edit page.
     *
     * Only draft/ordered purchase orders should be editable.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        if (in_array($purchaseOrder->status, ['received', 'cancelled'], true)) {
            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->withErrors('Received or cancelled purchase orders cannot be edited.');
        }

        $purchaseOrder->load('items');

        return view('purchase.orders.edit', [
            'purchaseOrder' => $purchaseOrder,
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update purchase order.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (in_array($purchaseOrder->status, ['received', 'cancelled'], true)) {
            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->withErrors('Received or cancelled purchase orders cannot be updated.');
        }

        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'expected_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity_ordered' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($data, $purchaseOrder) {
                $totalAmount = collect($data['items'])->sum(function ($item) {
                    return (int) $item['quantity_ordered'] * (float) $item['unit_cost'];
                });

                $purchaseOrder->update([
                    'supplier_id' => $data['supplier_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'expected_at' => $data['expected_at'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'total_amount' => $totalAmount,
                ]);

                $purchaseOrder->items()->delete();

                foreach ($data['items'] as $item) {
                    $quantity = (int) $item['quantity_ordered'];
                    $unitCost = (float) $item['unit_cost'];

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => null,
                        'quantity_ordered' => $quantity,
                        'quantity_received' => 0,
                        'unit_cost' => $unitCost,
                        'total_cost' => $quantity * $unitCost,
                    ]);
                }
            });

            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order updated successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Receive purchase order and increase product stock.
     */
    public function receive(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->withErrors('This purchase order has already been received.');
        }

        if ($purchaseOrder->status === 'cancelled') {
            return back()->withErrors('Cancelled purchase order cannot be received.');
        }

        try {
            DB::transaction(function () use ($purchaseOrder) {
                $purchaseOrder->load(['items.product', 'warehouse']);

                $user = Auth::user() ?? User::first();

                if (! $user) {
                    throw new RuntimeException('No user found. Please create or seed a user first.');
                }

                foreach ($purchaseOrder->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);

                    $stockBefore = (int) $product->stock_qty;
                    $receivedQty = (int) $item->quantity_ordered;
                    $stockAfter = $stockBefore + $receivedQty;

                    $product->update([
                        'stock_qty' => $stockAfter,
                    ]);

                    $item->update([
                        'quantity_received' => $receivedQty,
                    ]);

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'product_variant_id' => null,
                        'warehouse_id' => $purchaseOrder->warehouse_id,
                        'user_id' => $user->id,
                        'type' => 'in',
                        'quantity' => $receivedQty,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'reference' => $purchaseOrder->po_number,
                        'reason' => 'purchase_order_received',
                        'notes' => 'Stock received from purchase order.',
                    ]);
                }

                $purchaseOrder->update([
                    'status' => 'received',
                    'received_at' => now()->toDateString(),
                ]);
            });

            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order received and stock updated successfully.');
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Cancel purchase order.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->withErrors('Received purchase order cannot be cancelled.');
        }

        $purchaseOrder->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase order cancelled successfully.');
    }

    /**
     * Generate unique purchase order number.
     */
    private function generatePoNumber(): string
    {
        return 'PO-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    }
}
