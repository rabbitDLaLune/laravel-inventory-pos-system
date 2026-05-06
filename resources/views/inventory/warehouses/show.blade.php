@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Warehouse Details</h2>
            <p class="text-gray-500">{{ $warehouse->name }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('warehouses.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                Back
            </a>

            <a href="{{ route('warehouses.edit', $warehouse) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Name</p>
                <p class="font-semibold">{{ $warehouse->name }}</p>
            </div>

            <div>
                <p class="text-gray-500">Code</p>
                <p class="font-semibold">{{ $warehouse->code }}</p>
            </div>

            <div>
                <p class="text-gray-500">Phone</p>
                <p class="font-semibold">{{ $warehouse->phone ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Status</p>
                <p class="font-semibold">
                    {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500">Address</p>
                <p class="font-semibold">{{ $warehouse->address ?? '-' }}</p>
            </div>
        </div>
    </div>
@endsection
