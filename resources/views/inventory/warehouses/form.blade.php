<div>
    <label class="block text-sm font-medium mb-1">Warehouse Name</label>
    <input
        type="text"
        name="name"
        value="{{ old('name', $warehouse->name ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Warehouse Code</label>
    <input
        type="text"
        name="code"
        value="{{ old('code', $warehouse->code ?? '') }}"
        placeholder="Example: MAIN"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Phone</label>
    <input
        type="text"
        name="phone"
        value="{{ old('phone', $warehouse->phone ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Address</label>
    <textarea
        name="address"
        rows="3"
        class="w-full border rounded-lg px-3 py-2"
    >{{ old('address', $warehouse->address ?? '') }}</textarea>
</div>

<div class="md:col-span-2">
    <label class="flex items-center gap-2">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked(old('is_active', $warehouse->is_active ?? true))
        >
        <span>Active warehouse</span>
    </label>
</div>
