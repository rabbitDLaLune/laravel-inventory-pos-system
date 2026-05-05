@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Products</h2>
            <p class="text-gray-500">Manage inventory products</p>
        </div>

        <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Add Product
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Category</th>
                    <th class="text-left p-3">Supplier</th>
                    <th class="text-left p-3">SKU</th>
                    <th class="text-left p-3">Barcode</th>
                    <th class="text-left p-3">Stock</th>
                    <th class="text-left p-3">Price</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-b">
                        <td class="p-3 font-medium">{{ $product->name }}</td>
                        <td class="p-3">{{ $product->category?->name ?? '-' }}</td>
                        <td class="p-3">{{ $product->supplier?->name ?? '-' }}</td>
                        <td class="p-3">{{ $product->sku }}</td>
                        <td class="p-3">{{ $product->barcode ?? '-' }}</td>
                        <td class="p-3">
                            @if ($product->stock_qty <= $product->low_stock_threshold)
                                <span class="text-red-600 font-semibold">{{ $product->stock_qty }}</span>
                            @else
                                {{ $product->stock_qty }}
                            @endif
                        </td>
                        <td class="p-3">RM {{ number_format($product->selling_price, 2) }}</td>
                        <td class="p-3">
                            @if ($product->is_active)
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-600">Inactive</span>
                            @endif
                        </td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="text-blue-600">Edit</a>

                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-4 text-gray-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
