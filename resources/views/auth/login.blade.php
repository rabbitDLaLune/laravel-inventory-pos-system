<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventory POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow p-8">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900">Inventory POS</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to continue</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@inventorypos.com"
                    class="w-full border rounded-lg px-3 py-2"
                    autofocus
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Enter password"
                    class="w-full border rounded-lg px-3 py-2"
                >
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1">
                <span>Remember me</span>
            </label>

            <button class="w-full bg-slate-900 text-white py-3 rounded-lg" type="submit">
                Login
            </button>
        </form>

        <div class="mt-6 text-xs text-slate-500 bg-slate-50 rounded-lg p-3">
            <p class="font-semibold mb-1">Sample Login</p>
            <p>Admin: admin@inventorypos.com</p>
            <p>Cashier: cashier@inventorypos.com</p>
            <p>Manager: manager@inventorypos.com</p>
            <p>Password: password</p>
        </div>
    </div>
</body>
</html>
