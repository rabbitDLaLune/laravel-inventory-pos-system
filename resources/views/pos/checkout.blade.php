@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Checkout</h2>
        <p class="text-gray-500">Confirm payment and complete sale</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-5">
            <h3 class="font-bold mb-4">Order Summary</h3>

            <div class="space-y-3">
                @foreach ($cartItems as $item)
                    <div class="flex justify-between border-b pb-3">
                        <div>
                            <h4 class="font-semibold">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-500">
                                {{ $item['quantity'] }} x RM {{ number_format($item['unit_price'], 2) }}
                            </p>
                        </div>

                        <p class="font-bold">
                            RM {{ number_format($item['line_total'], 2) }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between text-xl font-bold mt-5">
                <span>Total</span>
                <span>RM {{ number_format($subtotal, 2) }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 h-fit">
            <h3 class="font-bold mb-4">Payment</h3>

            <form action="{{ route('pos.checkout.process') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full border rounded-lg px-3 py-2">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="qr">QR Payment</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Amount Paid</label>
                    <input
                        type="number"
                        step="0.01"
                        name="amount_paid"
                        value="{{ old('amount_paid', $subtotal) }}"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Payment Reference</label>
                    <input
                        type="text"
                        name="payment_reference"
                        value="{{ old('payment_reference') }}"
                        placeholder="For QR/card transaction reference"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Customer Name</label>
                    <input
                        type="text"
                        name="customer_name"
                        value="{{ old('customer_name') }}"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Customer Phone</label>
                    <input
                        type="text"
                        name="customer_phone"
                        value="{{ old('customer_phone') }}"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                <button class="w-full bg-green-600 text-white py-3 rounded-lg" type="submit">
                    Complete Sale
                </button>

                <a href="{{ route('pos.index') }}" class="block text-center bg-gray-200 py-3 rounded-lg">
                    Back to POS
                </a>
            </form>
        </div>
    </div>
@endsection
