@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Purchase Orders</h2>
            <p class="text-gray-500">Manage stock purchases from suppliers</p>
        </div>

        <a href="{{ route('purchase-orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            New Purchase Order
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">PO Number</th>
                    <th class="text-left p-3">Supplier</th>
                    <th class="text-left p-3">Warehouse</th>
                    <th class="text-left p-3">Ordered Date</th>
                    <th class="text-right p-3">Total</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($purchaseOrders as $purchaseOrder)
                    <tr class="border-b">
                        <td class="p-3 font-medium">{{ $purchaseOrder->po_number }}</td>
                        <td class="p-3">{{ $purchaseOrder->supplier?->name ?? '-' }}</td>
                        <td class="p-3">{{ $purchaseOrder->warehouse?->name ?? '-' }}</td>
                        <td class="p-3">
                            {{ $purchaseOrder->ordered_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="p-3 text-right font-semibold">
                            RM {{ number_format($purchaseOrder->total_amount, 2) }}
                        </td>
                        <td class="p-3">
                            @if ($purchaseOrder->status === 'received')
                                <span class="text-green-600 font-medium">Received</span>
                            @elseif ($purchaseOrder->status === 'cancelled')
                                <span class="text-red-600 font-medium">Cancelled</span>
                            @else
                                <span class="text-blue-600 font-medium">{{ ucfirst($purchaseOrder->status) }}</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="text-green-600">
                                    View
                                </a>

                                @if (! in_array($purchaseOrder->status, ['received', 'cancelled']))
                                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="text-blue-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Cancel this purchase order?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-red-600" type="submit">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-gray-500">
                            No purchase orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
@endsection
