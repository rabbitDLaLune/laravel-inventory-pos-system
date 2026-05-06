@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">New Stock Adjustment</h2>
        <p class="text-gray-500">Add stock, reduce stock, or set exact stock quantity</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
        <form action="{{ route('stock-adjustments.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Product</label>
                <select name="product_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                            {{ $product->name }} - {{ $product->sku }} - Current Stock: {{ $product->stock_qty }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Warehouse</label>
                <select name="warehouse_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                            {{ $warehouse->name }} - {{ $warehouse->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Adjustment Type</label>
                <select name="type" class="w-full border rounded-lg px-3 py-2">
                    <option value="in" @selected(old('type') === 'in')>Stock In - Add stock</option>
                    <option value="out" @selected(old('type') === 'out')>Stock Out - Reduce stock</option>
                    <option value="adjustment" @selected(old('type') === 'adjustment')>Adjustment - Set exact stock quantity</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Quantity</label>
                <input
                    type="number"
                    name="quantity"
                    min="0"
                    value="{{ old('quantity', 0) }}"
                    class="w-full border rounded-lg px-3 py-2"
                >

                <p class="text-xs text-gray-500 mt-1">
                    For adjustment type, quantity means the final stock quantity.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Reason</label>
                <input
                    type="text"
                    name="reason"
                    value="{{ old('reason') }}"
                    placeholder="Example: New stock arrived, damaged item, stock count correction"
                    class="w-full border rounded-lg px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea
                    name="notes"
                    rows="3"
                    class="w-full border rounded-lg px-3 py-2"
                >{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Save Adjustment
                </button>

                <a href="{{ route('stock-movements.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
