# Laravel Inventory POS System

A Laravel-based Inventory Management and Point of Sale (POS) system. This project includes product management, category management, stock tracking, POS cart operations, and product search using barcode, SKU, or product name.

## Features

- Dashboard overview
- Product management
- Category management
- Supplier and warehouse database structure
- POS terminal screen
- Product search by name, SKU, or barcode
- Add products to cart
- Update cart quantity
- Remove cart items
- Clear cart
- Checkout structure
- Sales, sale items, payments, and inventory movement database structure
- User role structure for admin, manager, and cashier

## Tech Stack

- Laravel
- PHP
- MySQL
- Blade
- Tailwind CSS CDN
- JavaScript
- Composer
- Git and GitHub

## Database

The system uses MySQL.

Main tables include:

- users
- categories
- suppliers
- warehouses
- products
- product_variants
- inventory_movements
- sales
- sale_items
- payments
- purchase_orders
- purchase_order_items
- audit_logs

## Sample Data

If seeders are used, the system includes sample users and products.

Example users:

```text
Admin: admin@inventorypos.com
Cashier: cashier@inventorypos.com
Manager: manager@inventorypos.com
Password: password
