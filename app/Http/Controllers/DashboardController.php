<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Warehouse;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'totalWarehouses' => Warehouse::count(),
            'totalSales' => Sale::count(),
            'lowStockProducts' => Product::whereColumn('stock_qty', '<=', 'low_stock_threshold')->count(),
            'recentProducts' => Product::latest()->take(5)->get(),
        ]);
    }
}
