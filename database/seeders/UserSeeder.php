<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inventorypos.com'],
            [
                'name' => 'System Admin',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@inventorypos.com'],
            [
                'name' => 'Main Cashier',
                'password' => 'password',
                'role' => 'cashier',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@inventorypos.com'],
            [
                'name' => 'Store Manager',
                'password' => 'password',
                'role' => 'manager',
                'is_active' => true,
            ]
        );
    }
}
