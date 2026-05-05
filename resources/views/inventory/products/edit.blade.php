@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Product</h2>
        <p class="text-gray-500">{{ $product->name }}</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('products.update', $product) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            @include('inventory.products.form')

            <div class="md:col-span-2 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Update Product
                </button>

                <a href="{{ route('products.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
