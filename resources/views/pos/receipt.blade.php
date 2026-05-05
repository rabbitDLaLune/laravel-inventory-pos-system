@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Receipt</h2>
            <p class="text-gray-500">{{ $sale->invoice_number }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('pos.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                New Sale
            </a>

            <button onclick="window.print()" class="bg-gray-200 px-4 py-2 rounded-lg">
                Print
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
        <div class="text-center border-b pb-4 mb-4">
            <h3 class="text-xl font-bold">Inventory POS</h3>
            <p class="text-sm text-gray-500">Receipt</p>
        </div>

        <div class="text-sm mb-4 space-y-1">
            <p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $sale->completed_at?->format('d M Y, h:i A') }}</p>
            <p><strong>Cashier:</strong> {{ $sale->user?->name }}</p>
            <p><strong>Customer:</strong> {{ $sale->customer_name ?? '-' }}</p>
        </div>

        <table class="w-full text-sm border-t border-b">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-2">Item</th>
                    <th class="text-center p-2">Qty</th>
                    <th class="text-right p-2">Price</th>
                    <th class="text-right p-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr class="border-b">
                        <td class="p-2">{{ $item->product_name }}</td>
                        <td class="p-2 text-center">{{ $item->quantity }}</td>
                        <td class="p-2 text-right">RM {{ number_format($item->unit_price, 2) }}</td>
                        <td class="p-2 text-right">RM {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>RM {{ number_format($sale->subtotal, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Tax</span>
                <span>RM {{ number_format($sale->tax_amount, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Discount</span>
                <span>RM {{ number_format($sale->discount_amount, 2) }}</span>
            </div>

            <div class="flex justify-between text-lg font-bold border-t pt-2">
                <span>Total</span>
                <span>RM {{ number_format($sale->total_amount, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Paid</span>
                <span>RM {{ number_format($sale->amount_paid, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Change</span>
                <span>RM {{ number_format($sale->change_amount, 2) }}</span>
            </div>
        </div>

        <div class="text-center text-sm text-gray-500 mt-6">
            Thank you for your purchase.
        </div>
    </div>
@endsection
