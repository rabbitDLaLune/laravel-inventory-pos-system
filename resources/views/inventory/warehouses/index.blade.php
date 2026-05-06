@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Warehouses</h2>
            <p class="text-gray-500">Manage store branches or stock locations</p>
        </div>

        <a href="{{ route('warehouses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Add Warehouse
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Code</th>
                    <th class="text-left p-3">Phone</th>
                    <th class="text-left p-3">Address</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($warehouses as $warehouse)
                    <tr class="border-b">
                        <td class="p-3 font-medium">{{ $warehouse->name }}</td>
                        <td class="p-3">{{ $warehouse->code }}</td>
                        <td class="p-3">{{ $warehouse->phone ?? '-' }}</td>
                        <td class="p-3">{{ $warehouse->address ?? '-' }}</td>
                        <td class="p-3">
                            @if ($warehouse->is_active)
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-600">Inactive</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('warehouses.show', $warehouse) }}" class="text-green-600">
                                    View
                                </a>

                                <a href="{{ route('warehouses.edit', $warehouse) }}" class="text-blue-600">
                                    Edit
                                </a>

                                <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('Delete this warehouse?')">
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
                            No warehouses found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $warehouses->links() }}
        </div>
    </div>
@endsection
