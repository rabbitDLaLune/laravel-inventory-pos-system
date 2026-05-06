@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Sales History</h2>
            <p class="text-gray-500">View all completed POS transactions</p>
        </div>

        <a href="{{ route('pos.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            New Sale
        </a>
    </div>

    {{-- Sales table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Invoice</th>
                    <th class="text-left p-3">Date</th>
                    <th class="text-left p-3">Cashier</th>
                    <th class="text-left p-3">Warehouse</th>
                    <th class="text-left p-3">Payment</th>
                    <th class="text-right p-3">Total</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sales as $sale)
                    @php
                        $payment = $sale->payments->first();
                    @endphp

                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium">
                            {{ $sale->invoice_number }}
                        </td>

                        <td class="p-3">
                            {{ $sale->completed_at?->format('d M Y, h:i A') ?? $sale->created_at->format('d M Y, h:i A') }}
                        </td>

                        <td class="p-3">
                            {{ $sale->user?->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $sale->warehouse?->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $payment ? strtoupper($payment->method) : '-' }}
                        </td>

                        <td class="p-3 text-right font-semibold">
                            RM {{ number_format($sale->total_amount, 2) }}
                        </td>

                        <td class="p-3">
                            @if ($sale->status === 'completed')
                                <span class="text-green-600 font-medium">Completed</span>
                            @elseif ($sale->status === 'refunded')
                                <span class="text-yellow-600 font-medium">Refunded</span>
                            @elseif ($sale->status === 'cancelled')
                                <span class="text-red-600 font-medium">Cancelled</span>
                            @else
                                <span class="text-gray-600 font-medium">{{ ucfirst($sale->status) }}</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600">
                                    View
                                </a>

                                <a href="{{ route('sales.receipt', $sale) }}" class="text-green-600">
                                    Receipt
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-gray-500">
                            No sales found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $sales->links() }}
        </div>
    </div>
@endsection
