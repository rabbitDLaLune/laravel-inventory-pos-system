<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Supplier</label>
        <select name="supplier_id" class="w-full border rounded-lg px-3 py-2">
            <option value="">Select supplier</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchaseOrder->supplier_id ?? '') == $supplier->id)>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Warehouse</label>
        <select name="warehouse_id" class="w-full border rounded-lg px-3 py-2">
            <option value="">Select warehouse</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" @selected(old('warehouse_id', $purchaseOrder->warehouse_id ?? '') == $warehouse->id)>
                    {{ $warehouse->name }} - {{ $warehouse->code }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Expected Date</label>
        <input
            type="date"
            name="expected_at"
            value="{{ old('expected_at', isset($purchaseOrder) && $purchaseOrder->expected_at ? $purchaseOrder->expected_at->format('Y-m-d') : '') }}"
            class="w-full border rounded-lg px-3 py-2"
        >
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Notes</label>
        <textarea
            name="notes"
            rows="3"
            class="w-full border rounded-lg px-3 py-2"
        >{{ old('notes', $purchaseOrder->notes ?? '') }}</textarea>
    </div>
</div>

<div class="mt-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold">Purchase Items</h3>

        <button type="button" id="addItemBtn" class="bg-gray-800 text-white px-4 py-2 rounded-lg">
            Add Item
        </button>
    </div>

    <div id="itemsContainer" class="space-y-3">
        @php
            $oldItems = old('items');

            if (! $oldItems && isset($purchaseOrder)) {
                $oldItems = $purchaseOrder->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity_ordered' => $item->quantity_ordered,
                        'unit_cost' => $item->unit_cost,
                    ];
                })->toArray();
            }

            if (! $oldItems) {
                $oldItems = [
                    [
                        'product_id' => '',
                        'quantity_ordered' => 1,
                        'unit_cost' => 0,
                    ],
                ];
            }
        @endphp

        @foreach ($oldItems as $index => $item)
            <div class="item-row grid grid-cols-1 md:grid-cols-4 gap-3 border rounded-lg p-3">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Product</label>
                    <select name="items[{{ $index }}][product_id]" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(($item['product_id'] ?? '') == $product->id)>
                                {{ $product->name }} - {{ $product->sku }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Qty</label>
                    <input
                        type="number"
                        name="items[{{ $index }}][quantity_ordered]"
                        min="1"
                        value="{{ $item['quantity_ordered'] ?? 1 }}"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Unit Cost</label>
                    <input
                        type="number"
                        step="0.01"
                        name="items[{{ $index }}][unit_cost]"
                        min="0"
                        value="{{ $item['unit_cost'] ?? 0 }}"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                <div class="md:col-span-4 flex justify-end">
                    <button type="button" class="remove-item bg-red-100 text-red-700 px-3 py-1 rounded">
                        Remove
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const products = @json($products);
        const container = document.getElementById('itemsContainer');
        const addItemBtn = document.getElementById('addItemBtn');

        function productOptions() {
            return products.map(product => {
                return `<option value="${product.id}">${product.name} - ${product.sku}</option>`;
            }).join('');
        }

        function reindexRows() {
            const rows = container.querySelectorAll('.item-row');

            rows.forEach((row, index) => {
                row.querySelectorAll('select, input').forEach(input => {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
                });
            });
        }

        addItemBtn.addEventListener('click', function () {
            const index = container.querySelectorAll('.item-row').length;

            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-1 md:grid-cols-4 gap-3 border rounded-lg p-3';

            row.innerHTML = `
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Product</label>
                    <select name="items[${index}][product_id]" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select product</option>
                        ${productOptions()}
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Qty</label>
                    <input type="number" name="items[${index}][quantity_ordered]" min="1" value="1" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Unit Cost</label>
                    <input type="number" step="0.01" name="items[${index}][unit_cost]" min="0" value="0" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="md:col-span-4 flex justify-end">
                    <button type="button" class="remove-item bg-red-100 text-red-700 px-3 py-1 rounded">
                        Remove
                    </button>
                </div>
            `;

            container.appendChild(row);
        });

        container.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item')) {
                const rows = container.querySelectorAll('.item-row');

                if (rows.length <= 1) {
                    alert('At least one item is required.');
                    return;
                }

                event.target.closest('.item-row').remove();
                reindexRows();
            }
        });
    });
</script>
