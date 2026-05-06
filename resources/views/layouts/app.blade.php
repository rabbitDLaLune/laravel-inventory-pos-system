<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Inventory POS') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-slate-900 text-white p-5 hidden md:block">
            <h1 class="text-xl font-bold mb-8">Inventory POS</h1>

            @if (auth()->check())
                <div class="mb-6 rounded-lg bg-slate-800 p-3 text-sm">
                    <p class="font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-slate-300 capitalize">{{ auth()->user()->role }}</p>
                </div>
            @endif

            <nav class="space-y-2">
                {{-- Admin and manager only --}}
                @if (in_array(auth()->user()?->role, ['admin', 'manager']))
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Dashboard
                    </a>
                @endif

                {{-- All roles --}}
                <a href="{{ route('pos.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                    POS
                </a>

                {{-- All roles --}}
                <a href="{{ route('sales.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                    Sales
                </a>

                {{-- Admin and manager only --}}
                @if (in_array(auth()->user()?->role, ['admin', 'manager']))
                    <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Products
                    </a>

                    <a href="{{ route('categories.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Categories
                    </a>

                    <a href="{{ route('suppliers.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Suppliers
                    </a>

                    <a href="{{ route('purchase-orders.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Purchase Orders
                    </a>

                    <a href="{{ route('warehouses.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Warehouses
                    </a>

                    <a href="{{ route('stock-movements.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Stock Movements
                    </a>

                    @if (auth()->user()?->role === 'admin')
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                            Users
                        </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                        Reports
                    </a>
                @endif
            </nav>

            @if (auth()->check())
                <form action="{{ route('logout') }}" method="POST" class="mt-6">
                    @csrf

                    <button class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" type="submit">
                        Logout
                    </button>
                </form>
            @endif
        </aside>

        <main class="flex-1 p-6">
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
