# Laravel Inventory POS System

A Laravel-based Inventory Management and Point of Sale (POS) system. This project includes product management, category management, supplier management, warehouse management, stock tracking, purchase orders, POS cart operations, checkout, sales records, receipts, reports, and product search using barcode, SKU, or product name.

## Features

- Login and logout
- Role-based access control for admin, manager, and cashier
- Dashboard overview
- User management for admin
- Product management
- Category management
- Supplier management
- Warehouse management
- Stock movement history
- Manual stock adjustment
- Purchase order management
- Receive purchase order stock
- POS terminal screen
- Product search by name, SKU, or barcode
- Barcode scanner support for POS search
- Add products to cart
- Update cart quantity
- Remove cart items
- Clear cart
- Checkout process
- Payment method selection
- Save sales and sale items
- Save payment records
- Automatic stock deduction after sale
- Receipt page with print option
- Sales history
- Sale details page
- Reports overview
- Low stock product tracking
- Best-selling product report
- Inventory movement tracking

## User Roles

The system includes three main roles:

| Role | Access |
|---|---|
| Admin | Full system access including user management |
| Manager | Inventory, purchase, reports, sales, and POS access |
| Cashier | POS, checkout, sales, and receipt access |

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
- sessions
- password_reset_tokens

## Sample Data

If seeders are used, the system includes sample users and products.

Example users:

```text
Admin: admin@inventorypos.com
Cashier: cashier@inventorypos.com
Manager: manager@inventorypos.com
Password: password
```

Example product barcodes:

```text
9550000000011
9550000000028
9550000000035
9550000000042
```

## Installation

Clone the repository:

```bash
git clone https://github.com/rabbitDLaLune/laravel-inventory-pos-system.git
cd laravel-inventory-pos-system
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Copy the environment file:

```bash
cp .env.example .env
```

For Windows PowerShell:

```powershell
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the `.env` database section:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_pos
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

Start the Laravel development server:

```bash
php artisan serve
```

Open the system in your browser:

```text
http://localhost:8000
```

## Useful Commands

Run migrations:

```bash
php artisan migrate
```

Reset database and seed again:

```bash
php artisan migrate:fresh --seed
```

Clear Laravel cache:

```bash
php artisan optimize:clear
```

Start development server:

```bash
php artisan serve
```

Check registered routes:

```bash
php artisan route:list
```

## Main Modules

### POS Module

The POS module allows the cashier to search products by barcode, SKU, or product name, add items to cart, update quantities, complete checkout, save payment records, deduct stock, and generate receipts.

### Inventory Module

The inventory module manages products, categories, warehouses, stock movements, and manual stock adjustments.

### Purchase Module

The purchase module manages suppliers and purchase orders. When a purchase order is received, product stock is automatically increased and an inventory movement record is created.

### Sales Module

The sales module stores completed sales, sale items, payment records, receipts, and sale details.

### Reports Module

The reports module provides sales and inventory summaries, including revenue, order count, low stock products, best-selling products, recent sales, and stock movement summaries.

### User Management Module

The user management module allows admin users to create, edit, activate, deactivate, and manage system users with different roles.

## Current Status

Completed:

- Laravel project setup
- MySQL database connection
- Database migrations
- Model relationships
- Seeders and sample data
- Login and logout
- Role-based access control
- Admin user management
- Dashboard page
- Product management
- Category management
- Supplier management
- Warehouse management
- Stock movement history
- Manual stock adjustment
- Purchase order management
- Receive stock from purchase orders
- POS cart
- Product lookup by barcode, SKU, or name
- Checkout process
- Payment recording
- Stock deduction after sale
- Receipt page
- Sales history
- Sale details
- Reports page

Planned improvements:

- Settings module
- Receipt customization
- Export reports
- Advanced payment gateway integration
- QR payment verification
- Product image upload
- More detailed role permissions
- Automated tests

## Author

Khairulnizam
