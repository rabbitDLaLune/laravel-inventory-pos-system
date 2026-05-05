<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::updateOrCreate(
            ['name' => 'Default Supplier'],
            [
                'email' => 'supplier@example.com',
                'phone' => '0123456789',
                'address' => 'Penang, Malaysia',
                'payment_terms' => 'cash',
                'is_active' => true,
            ]
        );
    }
}
