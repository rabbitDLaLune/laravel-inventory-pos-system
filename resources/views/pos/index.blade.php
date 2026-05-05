@extends('layouts.app')

@section('content')
    {{-- ============================================================
        POS PAGE HEADER
        - Shows page title
        - Provides Clear Cart button
    ============================================================ --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">POS Terminal</h2>
            <p class="text-gray-500">Search manually or scan barcode to add product</p>
        </div>

        {{-- Clear all items from cart --}}
        <form action="{{ route('pos.cart.clear') }}" method="POST" onsubmit="return confirm('Clear all cart items?')">
            @csrf
            <button class="bg-red-100 text-red-700 px-4 py-2 rounded-lg" type="submit">
                Clear Cart
            </button>
        </form>
    </div>

    {{-- ============================================================
        MAIN POS LAYOUT
        Left side: Search + quick products
        Right side: Cart
    ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ========================================================
            LEFT SIDE: PRODUCT SEARCH + QUICK PRODUCTS
        ======================================================== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Search / Barcode Scanner Section --}}
            <div class="bg-white rounded-xl shadow p-5">
                <label class="block text-sm font-medium mb-2">
                    Barcode / SKU / Product Name
                </label>

                {{--
                    Manual type:
                    - Shows search results
                    - User clicks Add button

                    Barcode scanner:
                    - Scanner types very fast and sends Enter
                    - System auto-adds item to cart
                --}}
                <input
                    id="productSearch"
                    type="text"
                    autocomplete="off"
                    autofocus
                    placeholder="Scan barcode or type product name..."
                    class="w-full border rounded-lg px-4 py-3 text-lg"
                >

                <p class="text-xs text-gray-500 mt-2">
                    Manual typing will show results. Barcode scanner will auto-add when Enter is detected.
                </p>

                {{-- Search results will be inserted here using JavaScript --}}
                <div id="searchResults" class="mt-4 space-y-2"></div>
            </div>

            {{-- Quick Products Section --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold mb-4">Quick Products</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse ($products as $product)
                        {{-- Add quick product directly to cart --}}
                        <form action="{{ route('pos.cart.add') }}" method="POST" class="border rounded-xl p-4 hover:shadow">
                            @csrf

                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">

                            <h4 class="font-semibold">{{ $product->name }}</h4>

                            <p class="text-sm text-gray-500">
                                SKU: {{ $product->sku }}
                            </p>

                            <p class="text-sm text-gray-500">
                                Barcode: {{ $product->barcode ?? '-' }}
                            </p>

                            <p class="text-sm mt-2">
                                Stock: {{ $product->stock_qty }}
                            </p>

                            <p class="text-lg font-bold mt-2">
                                RM {{ number_format($product->selling_price, 2) }}
                            </p>

                            <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded-lg" type="submit">
                                Add
                            </button>
                        </form>
                    @empty
                        <p class="text-gray-500">No products available.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ========================================================
            RIGHT SIDE: CART
        ======================================================== --}}
        <div class="bg-white rounded-xl shadow p-5 h-fit">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold">Cart</h3>
                <span class="text-sm text-gray-500">{{ $cartCount }} item(s)</span>
            </div>

            {{-- Cart Item List --}}
            <div class="space-y-3">
                @forelse ($cartItems as $item)
                    <div class="border rounded-lg p-3">

                        {{-- Cart item name, price and remove button --}}
                        <div class="flex justify-between gap-3">
                            <div>
                                <h4 class="font-semibold">{{ $item['name'] }}</h4>

                                <p class="text-xs text-gray-500">
                                    {{ $item['sku'] }}
                                </p>

                                <p class="text-sm">
                                    RM {{ number_format($item['unit_price'], 2) }}
                                </p>
                            </div>

                            {{-- Remove item from cart --}}
                            <form action="{{ route('pos.cart.remove') }}" method="POST">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">

                                <button class="text-red-600 text-sm" type="submit">
                                    Remove
                                </button>
                            </form>
                        </div>

                        {{-- Quantity update and line total --}}
                        <div class="flex items-center justify-between mt-3">

                            {{-- Update item quantity --}}
                            <form action="{{ route('pos.cart.update') }}" method="POST" class="flex items-center gap-2">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">

                                <input
                                    type="number"
                                    name="quantity"
                                    min="0"
                                    value="{{ $item['quantity'] }}"
                                    class="w-20 border rounded px-2 py-1"
                                >

                                <button class="bg-gray-200 px-3 py-1 rounded" type="submit">
                                    Update
                                </button>
                            </form>

                            <p class="font-bold">
                                RM {{ number_format($item['line_total'], 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Cart is empty.</p>
                @endforelse
            </div>

            {{-- Cart subtotal --}}
            <div class="border-t mt-5 pt-5">
                <div class="flex justify-between text-lg font-bold">
                    <span>Subtotal</span>
                    <span>RM {{ number_format($subtotal, 2) }}</span>
                </div>

                @if ($cartCount > 0)
                    <a
                        href="{{ route('pos.checkout') }}"
                        class="block text-center mt-5 w-full bg-green-600 text-white py-3 rounded-lg"
                    >
                        Checkout
                    </a>
                @else
                    <button
                        class="mt-5 w-full bg-green-600 text-white py-3 rounded-lg disabled:bg-gray-300"
                        type="button"
                        disabled
                    >
                        Checkout
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================
        POS SEARCH + BARCODE SCANNER SCRIPT

        Behavior:
        1. Manual typing:
           - User types slowly
           - Search result appears
           - User must click Add button

        2. Barcode scanner:
           - Scanner types very fast
           - Scanner usually sends Enter automatically
           - System auto-adds exact barcode/SKU product to cart
    ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search input field
            const searchInput = document.getElementById('productSearch');

            // Container where search results will be displayed
            const searchResults = document.getElementById('searchResults');

            // Timer used to delay search while user is typing
            let searchTimer = null;

            // Scanner detection variables
            let scanStartTime = null;
            let lastInputTime = null;

            /*
                Scanner speed detection setting.

                If the average typing speed is below this value,
                the system assumes input came from a barcode scanner.

                Manual typing is normally slower.
                Barcode scanners are normally very fast.
            */
            const SCANNER_SPEED_LIMIT_MS = 80;

            // Stop script if required elements cannot be found
            if (!searchInput || !searchResults) {
                console.error('POS search input or search result container not found.');
                return;
            }

            /**
             * Add selected product to cart.
             *
             * This creates a hidden form and submits it to Laravel.
             * We use this method because the cart add route is a POST route
             * and Laravel requires CSRF protection.
             */
            function addProductToCart(productId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('pos.cart.add') }}";

                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="product_id" value="${productId}">
                    <input type="hidden" name="quantity" value="1">
                `;

                document.body.appendChild(form);
                form.submit();
            }

            /**
             * Render product search results below the search input.
             *
             * This is used for manual typing.
             * User must click Add button manually.
             */
            function renderResults(products) {
                searchResults.innerHTML = '';

                if (!products.length) {
                    searchResults.innerHTML = '<p class="text-sm text-red-500">No product found.</p>';
                    return;
                }

                products.forEach(product => {
                    const div = document.createElement('div');
                    div.className = 'border rounded-lg p-3 flex items-center justify-between bg-gray-50';

                    div.innerHTML = `
                        <div>
                            <h4 class="font-semibold">${product.name}</h4>

                            <p class="text-sm text-gray-500">
                                SKU: ${product.sku} | Barcode: ${product.barcode ?? '-'}
                            </p>

                            <p class="text-sm">
                                Stock: ${product.stock_qty} | RM ${Number(product.selling_price).toFixed(2)}
                            </p>
                        </div>

                        <button
                            type="button"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg"
                            onclick="window.addProductToCart(${product.id})"
                        >
                            Add
                        </button>
                    `;

                    searchResults.appendChild(div);
                });
            }

            /**
             * Search product from Laravel lookup route.
             *
             * @param {string} query - Barcode, SKU, or product name
             * @param {boolean} autoAdd - True only when scanner is detected
             */
            async function lookupProduct(query, autoAdd = false) {
                const cleanQuery = query.trim();

                if (!cleanQuery) {
                    searchResults.innerHTML = '';
                    return;
                }

                try {
                    const url = `/products/lookup?q=${encodeURIComponent(cleanQuery)}`;
                    const response = await fetch(url);

                    if (!response.ok) {
                        searchResults.innerHTML = '<p class="text-sm text-red-500">Search request failed.</p>';
                        return;
                    }

                    const data = await response.json();
                    const products = data.products || [];

                    /*
                        Scanner mode:
                        Only auto-add if product barcode/SKU exactly matches.
                        This prevents wrong item from being added.
                    */
                    if (autoAdd) {
                        const exactProduct = products.find(product =>
                            product.barcode === cleanQuery || product.sku === cleanQuery
                        );

                        if (exactProduct) {
                            addProductToCart(exactProduct.id);
                            return;
                        }

                        searchResults.innerHTML = '<p class="text-sm text-red-500">Scanned product not found.</p>';
                        return;
                    }

                    /*
                        Manual typing mode:
                        Show results.
                        User must click Add button.
                    */
                    renderResults(products);
                } catch (error) {
                    console.error('POS lookup error:', error);
                    searchResults.innerHTML = '<p class="text-sm text-red-500">Search error. Check browser console.</p>';
                }
            }

            /**
             * Detect if input is likely from a barcode scanner.
             *
             * Most barcode scanners:
             * - type many characters very fast
             * - send Enter at the end
             */
            function isScannerInput() {
                const value = searchInput.value.trim();

                if (!value || !scanStartTime || !lastInputTime) {
                    return false;
                }

                const totalTime = lastInputTime - scanStartTime;
                const averageTime = totalTime / value.length;

                return value.length >= 6 && averageTime <= SCANNER_SPEED_LIMIT_MS;
            }

            // Make addProductToCart available to dynamically generated Add buttons
            window.addProductToCart = addProductToCart;

            /**
             * Live manual search while user types.
             *
             * This will show product results but will not auto-add.
             */
            searchInput.addEventListener('input', function () {
                const now = Date.now();

                /*
                    Reset scanner timing if:
                    - this is first input
                    - user paused too long
                */
                if (!scanStartTime || !lastInputTime || now - lastInputTime > 500) {
                    scanStartTime = now;
                }

                lastInputTime = now;

                clearTimeout(searchTimer);

                searchTimer = setTimeout(() => {
                    lookupProduct(searchInput.value, false);
                }, 250);
            });

            /**
             * Enter key behavior:
             *
             * Scanner:
             * - auto-add exact barcode/SKU product
             *
             * Manual typing:
             * - only show result list
             * - user clicks Add button manually
             */
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    if (isScannerInput()) {
                        lookupProduct(searchInput.value, true);
                    } else {
                        lookupProduct(searchInput.value, false);
                    }
                }
            });
        });
    </script>
@endsection
