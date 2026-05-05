@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p class="text-gray-500">Inventory and POS overview</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Products</p>
            <h3 class="text-2xl font-bold">{{ $totalProducts }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Categories</p>
            <h3 class="text-2xl font-bold">{{ $totalCategories }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Suppliers</p>
            <h3 class="text-2xl font-bold">{{ $totalSuppliers }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Warehouses</p>
            <h3 class="text-2xl font-bold">{{ $totalWarehouses }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Sales</p>
            <h3 class="text-2xl font-bold">{{ $totalSales }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Low Stock</p>
            <h3 class="text-2xl font-bold text-red-600">{{ $lowStockProducts }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-bold mb-4">Recent Products</h3>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">SKU</th>
                    <th class="text-left p-3">Barcode</th>
                    <th class="text-left p-3">Stock</th>
                    <th class="text-left p-3">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentProducts as $product)
                    <tr class="border-b">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3">{{ $product->sku }}</td>
                        <td class="p-3">{{ $product->barcode }}</td>
                        <td class="p-3">{{ $product->stock_qty }}</td>
                        <td class="p-3">RM {{ number_format($product->selling_price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-3 text-gray-500">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
