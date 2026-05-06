@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Supplier Details</h2>
            <p class="text-gray-500">{{ $supplier->name }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('suppliers.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                Back
            </a>

            <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h3 class="font-bold mb-4">Supplier Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Name</p>
                    <p class="font-semibold">{{ $supplier->name }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold">
                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold">{{ $supplier->email ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Phone</p>
                    <p class="font-semibold">{{ $supplier->phone ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Payment Terms</p>
                    <p class="font-semibold">{{ $supplier->payment_terms ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Address</p>
                    <p class="font-semibold">{{ $supplier->address ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Summary</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Products</span>
                        <span class="font-semibold">{{ $supplier->products->count() }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Purchase Orders</span>
                        <span class="font-semibold">{{ $supplier->purchaseOrders->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow mt-6 overflow-hidden">
        <div class="p-5 border-b">
            <h3 class="font-bold">Products From This Supplier</h3>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Product</th>
                    <th class="text-left p-3">SKU</th>
                    <th class="text-left p-3">Barcode</th>
                    <th class="text-right p-3">Stock</th>
                    <th class="text-right p-3">Selling Price</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($supplier->products as $product)
                    <tr class="border-b">
                        <td class="p-3 font-medium">{{ $product->name }}</td>
                        <td class="p-3">{{ $product->sku }}</td>
                        <td class="p-3">{{ $product->barcode ?? '-' }}</td>
                        <td class="p-3 text-right">{{ $product->stock_qty }}</td>
                        <td class="p-3 text-right">RM {{ number_format($product->selling_price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-gray-500">
                            No products linked to this supplier.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
