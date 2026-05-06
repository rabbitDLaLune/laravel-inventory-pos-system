@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">User Management</h2>
            <p class="text-gray-500">Manage admin, manager, and cashier accounts</p>
        </div>

        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Add User
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Email</th>
                    <th class="text-left p-3">Role</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Created</th>
                    <th class="text-left p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b">
                        <td class="p-3 font-medium">
                            {{ $user->name }}

                            @if (auth()->id() === $user->id)
                                <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                    You
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            {{ $user->email }}
                        </td>

                        <td class="p-3">
                            @if ($user->role === 'admin')
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Admin</span>
                            @elseif ($user->role === 'manager')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Manager</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">Cashier</span>
                            @endif
                        </td>

                        <td class="p-3">
                            @if ($user->is_active)
                                <span class="text-green-600 font-medium">Active</span>
                            @else
                                <span class="text-red-600 font-medium">Inactive</span>
                            @endif
                        </td>

                        <td class="p-3">
                            {{ $user->created_at?->format('d M Y') }}
                        </td>

                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('users.show', $user) }}" class="text-green-600">
                                    View
                                </a>

                                <a href="{{ route('users.edit', $user) }}" class="text-blue-600">
                                    Edit
                                </a>

                                @if (auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-red-600" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
