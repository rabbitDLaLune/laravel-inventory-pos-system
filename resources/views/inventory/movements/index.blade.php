@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Stock Movements</h2>
            <p class="text-gray-500">View stock in, stock out, and manual adjustments</p>
        </div>

        <a href="{{ route('stock-adjustments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            New Stock Adjustment
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow p-5 mb-5">
        <form method="GET" action="{{ route('stock-movements.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Movement Type</label>
                <select name="type" class="w-full border rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    <option value="in" @selected($selectedType === 'in')>Stock In</option>
                    <option value="out" @selected($selectedType === 'out')>Stock Out</option>
                    <option value="adjustment" @selected($selectedType === 'adjustment')>Adjustment</option>
                    <option value="transfer" @selected($selectedType === 'transfer')>Transfer</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Product</label>
                <select name="product_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">All Products</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected((string) $selectedProductId === (string) $product->id)>
                            {{ $product->name }} - {{ $product->sku }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Filter
                </button>

                <a href="{{ route('stock-movements.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Movement table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Date</th>
                    <th class="text-left p-3">Product</th>
                    <th class="text-left p-3">Warehouse</th>
                    <th class="text-left p-3">Type</th>
                    <th class="text-right p-3">Qty</th>
                    <th class="text-right p-3">Before</th>
                    <th class="text-right p-3">After</th>
                    <th class="text-left p-3">Reason</th>
                    <th class="text-left p-3">User</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($movements as $movement)
                    <tr class="border-b">
                        <td class="p-3">
                            {{ $movement->created_at->format('d M Y, h:i A') }}
                        </td>

                        <td class="p-3">
                            <p class="font-medium">{{ $movement->product?->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $movement->product?->sku ?? '-' }}</p>
                        </td>

                        <td class="p-3">
                            {{ $movement->warehouse?->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            @if ($movement->type === 'in')
                                <span class="text-green-600 font-medium">Stock In</span>
                            @elseif ($movement->type === 'out')
                                <span class="text-red-600 font-medium">Stock Out</span>
                            @elseif ($movement->type === 'adjustment')
                                <span class="text-blue-600 font-medium">Adjustment</span>
                            @else
                                <span class="text-gray-600 font-medium">{{ ucfirst($movement->type) }}</span>
                            @endif
                        </td>

                        <td class="p-3 text-right">
                            {{ $movement->quantity }}
                        </td>

                        <td class="p-3 text-right">
                            {{ $movement->stock_before }}
                        </td>

                        <td class="p-3 text-right font-semibold">
                            {{ $movement->stock_after }}
                        </td>

                        <td class="p-3">
                            <p>{{ $movement->reason ?? '-' }}</p>
                            @if ($movement->reference)
                                <p class="text-xs text-gray-500">Ref: {{ $movement->reference }}</p>
                            @endif
                        </td>

                        <td class="p-3">
                            {{ $movement->user?->name ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-4 text-gray-500">
                            No stock movements found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $movements->links() }}
        </div>
    </div>
@endsection
