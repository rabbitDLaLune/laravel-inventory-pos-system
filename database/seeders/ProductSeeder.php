<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::first();
        $food = Category::where('slug', 'food')->first();
        $drinks = Category::where('slug', 'drinks')->first();
        $snacks = Category::where('slug', 'snacks')->first();

        $products = [
            [
                'category_id' => $drinks?->id,
                'supplier_id' => $supplier?->id,
                'name' => 'Mineral Water 500ml',
                'sku' => 'DRK-001',
                'barcode' => '9550000000011',
                'description' => 'Bottle mineral water 500ml',
                'cost_price' => 0.60,
                'selling_price' => 1.20,
                'tax_rate' => 0,
                'stock_qty' => 100,
                'low_stock_threshold' => 10,
                'reorder_point' => 20,
                'unit' => 'bottle',
                'has_variants' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $drinks?->id,
                'supplier_id' => $supplier?->id,
                'name' => 'Milo Can',
                'sku' => 'DRK-002',
                'barcode' => '9550000000028',
                'description' => 'Milo drink can',
                'cost_price' => 1.50,
                'selling_price' => 2.80,
                'tax_rate' => 0,
                'stock_qty' => 80,
                'low_stock_threshold' => 10,
                'reorder_point' => 20,
                'unit' => 'can',
                'has_variants' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $food?->id,
                'supplier_id' => $supplier?->id,
                'name' => 'Gardenia Bread',
                'sku' => 'FOD-001',
                'barcode' => '9550000000035',
                'description' => 'White bread loaf',
                'cost_price' => 2.30,
                'selling_price' => 3.80,
                'tax_rate' => 0,
                'stock_qty' => 30,
                'low_stock_threshold' => 5,
                'reorder_point' => 10,
                'unit' => 'pack',
                'has_variants' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $snacks?->id,
                'supplier_id' => $supplier?->id,
                'name' => 'Potato Chips',
                'sku' => 'SNK-001',
                'barcode' => '9550000000042',
                'description' => 'Small packet potato chips',
                'cost_price' => 1.20,
                'selling_price' => 2.50,
                'tax_rate' => 0,
                'stock_qty' => 60,
                'low_stock_threshold' => 10,
                'reorder_point' => 15,
                'unit' => 'packet',
                'has_variants' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'slug' => Str::slug($product['name']),
                ])
            );
        }
    }
}
