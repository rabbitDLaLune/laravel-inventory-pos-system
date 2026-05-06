@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">New Purchase Order</h2>
        <p class="text-gray-500">Create order from supplier</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf

            @include('purchase.orders.form')

            <div class="mt-6 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Save Purchase Order
                </button>

                <a href="{{ route('purchase-orders.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
