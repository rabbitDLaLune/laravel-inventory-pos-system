@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Reports</h2>
            <p class="text-gray-500">Sales, inventory, and stock movement overview</p>
        </div>
    </div>

    {{-- Report summary cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Today Sales</p>
            <h3 class="text-2xl font-bold">{{ $todaySales }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Today Revenue</p>
            <h3 class="text-2xl font-bold">RM {{ number_format($todayRevenue, 2) }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Monthly Sales</p>
            <h3 class="text-2xl font-bold">{{ $monthlySales }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Monthly Revenue</p>
            <h3 class="text-2xl font-bold">RM {{ number_format($monthlyRevenue, 2) }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Orders</p>
            <h3 class="text-2xl font-bold">{{ $totalOrders }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <h3 class="text-2xl font-bold">RM {{ number_format($totalRevenue, 2) }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Low Stock Items</p>
            <h3 class="text-2xl font-bold text-red-600">{{ $lowStockProducts->count() }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Stock Movement Types</p>
            <h3 class="text-2xl font-bold">{{ $stockMovementSummary->count() }}</h3>
        </div>
    </div>

    {{-- Main report layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Best-selling products --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-bold">Best-Selling Products</h3>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left p-3">Product</th>
                        <th class="text-left p-3">SKU</th>
                        <th class="text-right p-3">Qty Sold</th>
                        <th class="text-right p-3">Sales</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bestSellingProducts as $product)
                        <tr class="border-b">
                            <td class="p-3 font-medium">{{ $product->product_name }}</td>
                            <td class="p-3">{{ $product->product_sku }}</td>
                            <td class="p-3 text-right">{{ $product->total_quantity }}</td>
                            <td class="p-3 text-right font-semibold">
                                RM {{ number_format($product->total_sales, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-gray-500">
                                No sales data available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Low stock products --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-bold">Low Stock Products</h3>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left p-3">Product</th>
                        <th class="text-left p-3">SKU</th>
                        <th class="text-right p-3">Stock</th>
                        <th class="text-right p-3">Threshold</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lowStockProducts as $product)
                        <tr class="border-b">
                            <td class="p-3 font-medium">{{ $product->name }}</td>
                            <td class="p-3">{{ $product->sku }}</td>
                            <td class="p-3 text-right text-red-600 font-semibold">
                                {{ $product->stock_qty }}
                            </td>
                            <td class="p-3 text-right">
                                {{ $product->low_stock_threshold }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-gray-500">
                                No low stock products.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Recent sales --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-bold">Recent Sales</h3>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left p-3">Invoice</th>
                        <th class="text-left p-3">Cashier</th>
                        <th class="text-left p-3">Payment</th>
                        <th class="text-right p-3">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentSales as $sale)
                        @php
                            $payment = $sale->payments->first();
                        @endphp

                        <tr class="border-b">
                            <td class="p-3">
                                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 font-medium">
                                    {{ $sale->invoice_number }}
                                </a>
                            </td>

                            <td class="p-3">{{ $sale->user?->name ?? '-' }}</td>

                            <td class="p-3">
                                {{ $payment ? strtoupper($payment->method) : '-' }}
                            </td>

                            <td class="p-3 text-right font-semibold">
                                RM {{ number_format($sale->total_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-gray-500">
                                No recent sales.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Stock movement summary --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-bold">Stock Movement Summary</h3>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left p-3">Type</th>
                        <th class="text-right p-3">Records</th>
                        <th class="text-right p-3">Quantity</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($stockMovementSummary as $movement)
                        <tr class="border-b">
                            <td class="p-3 font-medium capitalize">
                                {{ $movement->type }}
                            </td>

                            <td class="p-3 text-right">
                                {{ $movement->total_records }}
                            </td>

                            <td class="p-3 text-right font-semibold">
                                {{ $movement->total_quantity }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-gray-500">
                                No stock movement data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
