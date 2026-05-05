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

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                    Dashboard
                </a>

                <a href="{{ route('pos.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                    POS
                </a>

                <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                    Products
                </a>

                <a href="{{ route('categories.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700">
                    Categories
                </a>
            </nav>
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
