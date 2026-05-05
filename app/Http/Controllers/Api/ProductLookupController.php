<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductLookupController extends Controller
{
    public function lookup(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json([
                'products' => [],
            ]);
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('barcode', $query)
                    ->orWhere('sku', $query)
                    ->orWhere('name', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get([
                'id',
                'name',
                'sku',
                'barcode',
                'selling_price',
                'stock_qty',
            ]);

        return response()->json([
            'products' => $products,
        ]);
    }
}
