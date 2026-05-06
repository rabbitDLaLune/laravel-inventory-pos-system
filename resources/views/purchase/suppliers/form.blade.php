<div>
    <label class="block text-sm font-medium mb-1">Supplier Name</label>
    <input
        type="text"
        name="name"
        value="{{ old('name', $supplier->name ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Email</label>
    <input
        type="email"
        name="email"
        value="{{ old('email', $supplier->email ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Phone</label>
    <input
        type="text"
        name="phone"
        value="{{ old('phone', $supplier->phone ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Payment Terms</label>
    <input
        type="text"
        name="payment_terms"
        value="{{ old('payment_terms', $supplier->payment_terms ?? 'cash') }}"
        placeholder="Example: cash, net30, net60"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Address</label>
    <textarea
        name="address"
        rows="3"
        class="w-full border rounded-lg px-3 py-2"
    >{{ old('address', $supplier->address ?? '') }}</textarea>
</div>

<div class="md:col-span-2">
    <label class="flex items-center gap-2">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked(old('is_active', $supplier->is_active ?? true))
        >
        <span>Active supplier</span>
    </label>
</div>
