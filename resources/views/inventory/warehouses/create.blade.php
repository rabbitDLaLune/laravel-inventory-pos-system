@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Add Warehouse</h2>
        <p class="text-gray-500">Create a new stock location</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('warehouses.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            @include('inventory.warehouses.form')

            <div class="md:col-span-2 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Save Warehouse
                </button>

                <a href="{{ route('warehouses.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
