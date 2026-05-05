@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Add Product</h2>
        <p class="text-gray-500">Create new inventory product</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('products.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            @include('inventory.products.form')

            <div class="md:col-span-2 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Save Product
                </button>

                <a href="{{ route('products.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
