@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Suppliers</h2>
            <p class="text-gray-500">Manage product suppliers</p>
        </div>

        <a href="{{ route('suppliers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Add Supplier
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Email</th>
                    <th class="text-left p-3">Phone</th>
                    <th class="text-left p-3">Payment Terms</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr class="border-b">
                        <td class="p-3 font-medium">{{ $supplier->name }}</td>
                        <td class="p-3">{{ $supplier->email ?? '-' }}</td>
                        <td class="p-3">{{ $supplier->phone ?? '-' }}</td>
                        <td class="p-3">{{ $supplier->payment_terms ?? '-' }}</td>

                        <td class="p-3">
                            @if ($supplier->is_active)
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-600">Inactive</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="text-green-600">
                                    View
                                </a>

                                <a href="{{ route('suppliers.edit', $supplier) }}" class="text-blue-600">
                                    Edit
                                </a>

                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete this supplier?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-gray-500">
                            No suppliers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection
