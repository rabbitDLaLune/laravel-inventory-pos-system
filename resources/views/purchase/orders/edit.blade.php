@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Purchase Order</h2>
        <p class="text-gray-500">{{ $purchaseOrder->po_number }}</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST">
            @csrf
            @method('PUT')

            @include('purchase.orders.form')

            <div class="mt-6 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Update Purchase Order
                </button>

                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
