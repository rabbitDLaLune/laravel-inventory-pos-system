@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">User Details</h2>
            <p class="text-gray-500">{{ $user->name }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('users.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                Back
            </a>

            <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <h3 class="font-bold mb-4">Account Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Name</p>
                    <p class="font-semibold">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Role</p>
                    <p class="font-semibold capitalize">{{ $user->role }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Created At</p>
                    <p class="font-semibold">{{ $user->created_at?->format('d M Y, h:i A') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Updated At</p>
                    <p class="font-semibold">{{ $user->updated_at?->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Activity Summary</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sales</span>
                        <span class="font-semibold">{{ $user->sales->count() }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Purchase Orders</span>
                        <span class="font-semibold">{{ $user->purchaseOrders->count() }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Stock Movements</span>
                        <span class="font-semibold">{{ $user->inventoryMovements->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
