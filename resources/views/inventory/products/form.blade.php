<div>
    <label class="block text-sm font-medium mb-1">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">SKU</label>
    <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Barcode</label>
    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Category</label>
    <select name="category_id" class="w-full border rounded-lg px-3 py-2">
        <option value="">No category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Supplier</label>
    <select name="supplier_id" class="w-full border rounded-lg px-3 py-2">
        <option value="">No supplier</option>
        @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id ?? '') == $supplier->id)>
                {{ $supplier->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Unit</label>
    <input type="text" name="unit" value="{{ old('unit', $product->unit ?? 'pcs') }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Cost Price</label>
    <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? 0) }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Selling Price</label>
    <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? 0) }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Tax Rate (%)</label>
    <input type="number" step="0.01" name="tax_rate" value="{{ old('tax_rate', $product->tax_rate ?? 0) }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Stock Quantity</label>
    <input type="number" name="stock_qty" value="{{ old('stock_qty', $product->stock_qty ?? 0) }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Low Stock Threshold</label>
    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 10) }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Reorder Point</label>
    <input type="number" name="reorder_point" value="{{ old('reorder_point', $product->reorder_point ?? 5) }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Description</label>
    <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="md:col-span-2">
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
        <span>Active product</span>
    </label>
</div>
