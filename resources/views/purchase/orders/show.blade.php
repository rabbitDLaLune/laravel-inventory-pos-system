@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Purchase Order Details</h2>
            <p class="text-gray-500">{{ $purchaseOrder->po_number }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('purchase-orders.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                Back
            </a>

            @if (! in_array($purchaseOrder->status, ['received', 'cancelled']))
                <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>

                <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Receive this purchase order and update stock?')">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg" type="submit">
                        Receive Stock
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Purchase Order Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">PO Number</p>
                        <p class="font-semibold">{{ $purchaseOrder->po_number }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-semibold capitalize">{{ $purchaseOrder->status }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Supplier</p>
                        <p class="font-semibold">{{ $purchaseOrder->supplier?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Warehouse</p>
                        <p class="font-semibold">{{ $purchaseOrder->warehouse?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Created By</p>
                        <p class="font-semibold">{{ $purchaseOrder->user?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Ordered Date</p>
                        <p class="font-semibold">{{ $purchaseOrder->ordered_at?->format('d M Y') ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Expected Date</p>
                        <p class="font-semibold">{{ $purchaseOrder->expected_at?->format('d M Y') ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Received Date</p>
                        <p class="font-semibold">{{ $purchaseOrder->received_at?->format('d M Y') ?? '-' }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-gray-500">Notes</p>
                        <p class="font-semibold">{{ $purchaseOrder->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="p-5 border-b">
                    <h3 class="font-bold">Items</h3>
                </div>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left p-3">Product</th>
                            <th class="text-right p-3">Ordered</th>
                            <th class="text-right p-3">Received</th>
                            <th class="text-right p-3">Unit Cost</th>
                            <th class="text-right p-3">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchaseOrder->items as $item)
                            <tr class="border-b">
                                <td class="p-3">
                                    <p class="font-medium">{{ $item->product?->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->product?->sku ?? '-' }}</p>
                                </td>

                                <td class="p-3 text-right">{{ $item->quantity_ordered }}</td>
                                <td class="p-3 text-right">{{ $item->quantity_received }}</td>
                                <td class="p-3 text-right">RM {{ number_format($item->unit_cost, 2) }}</td>
                                <td class="p-3 text-right font-semibold">RM {{ number_format($item->total_cost, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 h-fit">
            <h3 class="font-bold mb-4">Summary</h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Items</span>
                    <span class="font-semibold">{{ $purchaseOrder->items->count() }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Total Amount</span>
                    <span class="font-semibold">RM {{ number_format($purchaseOrder->total_amount, 2) }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-semibold capitalize">{{ $purchaseOrder->status }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
