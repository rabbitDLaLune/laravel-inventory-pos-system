@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Sale Details</h2>
            <p class="text-gray-500">{{ $sale->invoice_number }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('sales.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                Back
            </a>

            <a href="{{ route('sales.receipt', $sale) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                View Receipt
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left section --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Sale information --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Sale Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Invoice Number</p>
                        <p class="font-semibold">{{ $sale->invoice_number }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-semibold capitalize">{{ $sale->status }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Date</p>
                        <p class="font-semibold">
                            {{ $sale->completed_at?->format('d M Y, h:i A') ?? $sale->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Cashier</p>
                        <p class="font-semibold">{{ $sale->user?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Warehouse</p>
                        <p class="font-semibold">{{ $sale->warehouse?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-semibold">
                            {{ $sale->customer_name ?? '-' }}

                            @if ($sale->customer_phone)
                                <span class="text-gray-500">({{ $sale->customer_phone }})</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Purchased items --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="p-5 border-b">
                    <h3 class="font-bold">Purchased Items</h3>
                </div>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left p-3">Product</th>
                            <th class="text-left p-3">SKU</th>
                            <th class="text-center p-3">Qty</th>
                            <th class="text-right p-3">Unit Price</th>
                            <th class="text-right p-3">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sale->items as $item)
                            <tr class="border-b">
                                <td class="p-3 font-medium">
                                    {{ $item->product_name }}
                                </td>

                                <td class="p-3">
                                    {{ $item->product_sku }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="p-3 text-right">
                                    RM {{ number_format($item->unit_price, 2) }}
                                </td>

                                <td class="p-3 text-right font-semibold">
                                    RM {{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right section --}}
        <div class="space-y-6">
            {{-- Payment details --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Payment Details</h3>

                @forelse ($sale->payments as $payment)
                    <div class="text-sm space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Method</span>
                            <span class="font-semibold uppercase">{{ $payment->method }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Amount</span>
                            <span class="font-semibold">RM {{ number_format($payment->amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-semibold capitalize">{{ $payment->status }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Reference</span>
                            <span class="font-semibold">{{ $payment->reference ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Paid At</span>
                            <span class="font-semibold">
                                {{ $payment->paid_at?->format('d M Y, h:i A') ?? '-' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No payment record found.</p>
                @endforelse
            </div>

            {{-- Sale summary --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Summary</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span>RM {{ number_format($sale->subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Tax</span>
                        <span>RM {{ number_format($sale->tax_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Discount</span>
                        <span>RM {{ number_format($sale->discount_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between border-t pt-3 text-lg font-bold">
                        <span>Total</span>
                        <span>RM {{ number_format($sale->total_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Amount Paid</span>
                        <span>RM {{ number_format($sale->amount_paid, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Change</span>
                        <span>RM {{ number_format($sale->change_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
