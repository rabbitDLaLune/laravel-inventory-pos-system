@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Add Category</h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-xl">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf

            @include('inventory.categories.form')

            <div class="flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg" type="submit">
                    Save Category
                </button>

                <a href="{{ route('categories.index') }}" class="bg-gray-200 px-4 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
