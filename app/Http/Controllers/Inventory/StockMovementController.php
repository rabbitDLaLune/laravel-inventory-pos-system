<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockMovementController extends Controller
{
    /**
     * Display stock movement history.
     */
    public function index(Request $request)
    {
        $movements = InventoryMovement::with(['product', 'productVariant', 'warehouse', 'user'])
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('product_id'), function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('inventory.movements.index', [
            'movements' => $movements,
            'products' => Product::orderBy('name')->get(),
            'selectedType' => $request->type,
            'selectedProductId' => $request->product_id,
        ]);
    }

    /**
     * Show manual stock adjustment form.
     */
    public function create()
    {
        return view('inventory.movements.create', [
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store manual stock adjustment.
     *
     * Type:
     * - in = add stock
     * - out = reduce stock
     * - adjustment = set stock to exact quantity
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                $product = Product::lockForUpdate()->findOrFail($data['product_id']);

                $user = Auth::user() ?? User::first();

                if (! $user) {
                    throw new RuntimeException('No user found. Please create or seed a user first.');
                }

                $stockBefore = (int) $product->stock_qty;
                $quantity = (int) $data['quantity'];

                if ($data['type'] === 'in') {
                    $stockAfter = $stockBefore + $quantity;
                } elseif ($data['type'] === 'out') {
                    if ($stockBefore < $quantity) {
                        throw new RuntimeException("Not enough stock for {$product->name}.");
                    }

                    $stockAfter = $stockBefore - $quantity;
                } else {
                    // Adjustment means set stock to exact quantity
                    $stockAfter = $quantity;
                    $quantity = abs($stockAfter - $stockBefore);
                }

                $product->update([
                    'stock_qty' => $stockAfter,
                ]);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'warehouse_id' => $data['warehouse_id'],
                    'user_id' => $user->id,
                    'type' => $data['type'],
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference' => 'MANUAL-' . now()->format('YmdHis'),
                    'reason' => $data['reason'],
                    'notes' => $data['notes'] ?? null,
                ]);
            });

            return redirect()
                ->route('stock-movements.index')
                ->with('success', 'Stock adjustment saved successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }
}
