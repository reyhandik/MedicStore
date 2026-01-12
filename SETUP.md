# MedicStore - Complete Setup Guide

## Project Overview
**Sistem Apotek Online Sederhana** (MedicStore) is a web-based online pharmacy system built with Laravel 11, Tailwind CSS, and MySQL.

## Technology Stack
- **PHP Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Tailwind CSS + Blade Templates
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage (public disk for images and prescriptions)

## Database Schema
- **users**: User accounts with roles (admin, pharmacist, patient)
- **categories**: Medicine categories
- **medicines**: Medicine inventory with pricing and recipe requirement flag
- **orders**: Customer orders with status tracking
- **order_details**: Line items for each order

---

## Installation & Setup Instructions

### Step 1: Navigate to Project Directory
```bash
cd c:\laragon\www\MedicStore
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Configuration
1. Copy `.env.example` to `.env`:
   ```bash
   copy .env.example .env
   ```

2. Update `.env` database configuration:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=MedicStore
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

### Step 4: Create Database
```sql
CREATE DATABASE MedicStore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5: Run Migrations
```bash
php artisan migrate
```

### Step 6: Seed Database (Sample Data)
```bash
php artisan db:seed
```

This creates:
- **Admin User**: admin@medicstore.com / password
- **Pharmacist User**: pharmacist@medicstore.com / password
- **Patient Users**: john@example.com / password, jane@example.com / password
- **6 Medicine Categories** with 10 sample medicines

### Step 7: Create Storage Symlink
```bash
php artisan storage:link
```

### Step 8: Compile Frontend Assets
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

### Step 9: Start Laravel Development Server
```bash
php artisan serve
```

Access the application at: **http://localhost:8000**

---

## User Roles & Access

### **Admin**
- Email: `admin@medicstore.com`
- Password: `password`
- Access:
  - Dashboard with sales reports
  - User management
  - Full medicine CRUD operations
  - View all orders

**Routes**:
- `/admin/dashboard` - Sales analytics
- `/medicines` - Manage all medicines

### **Pharmacist**
- Email: `pharmacist@medicstore.com`
- Password: `password`
- Access:
  - Pharmacist dashboard
  - View pending orders for verification
  - Verify orders and update stock
  - View low-stock alerts (< 10 items)
  - Manage medicines

**Routes**:
- `/pharmacist/dashboard` - Order management overview
- `/pharmacist/pending-orders` - Orders awaiting verification
- `/pharmacist/low-stock` - Low stock alerts

### **Patient**
- Email: `john@example.com` or `jane@example.com`
- Password: `password`
- Access:
  - Browse medicines catalog
  - Search and filter by category
  - Add medicines to cart
  - Checkout with prescription upload (if required)
  - View order history and tracking

**Routes**:
- `/` - Medicine catalog
- `/cart` - Shopping cart
- `/checkout` - Checkout process
- `/my-orders` - Order history
- `/dashboard` - Patient dashboard

---

## Key Features Implemented

### 1. **Role-Based Access Control (RBAC)**
- Middleware: `EnsureRole` - Validates user role before accessing protected routes
- Route protection using `route:admin`, `role:pharmacist`, `role:patient`

### 2. **Medicine Management (CRUD)**
- **Controllers**: `MedicineController`
- Admin/Pharmacist can:
  - Create medicines with image upload
  - Edit/update medicine details
  - Delete medicines
  - Mark medicines as requiring prescriptions

### 3. **Product Catalog**
- **Controller**: `CatalogController`
- Patients can:
  - Browse all medicines with pagination
  - Filter by category
  - Search by name or description
  - View medicine details

### 4. **Shopping Cart** (Session-based)
- **Controller**: `CartController`
- Features:
  - Add/remove items
  - Update quantities
  - Real-time stock validation
  - Session persistence

### 5. **Checkout & Orders**
- **Controller**: `CheckoutController`
- Flow:
  1. Patient reviews cart
  2. If any medicine requires recipe, upload prescription image
  3. Order is created with status "pending"
  4. Prescription file stored in `storage/app/public/prescriptions/`

### 6. **Order Verification**
- **Controller**: `OrderController::verify()`
- Pharmacist:
  - Reviews pending orders
  - Verifies stock availability
  - Updates order status to "verified"
  - Automatically decreases medicine stock

### 7. **Dashboards**
- **Admin Dashboard**: Sales reports, user count, top-selling medicines
- **Pharmacist Dashboard**: Order counts by status, low-stock alerts, recent pending orders
- **Patient Dashboard**: Order history, total spent, recent orders

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── MedicineController.php      # CRUD operations
│   │   ├── CatalogController.php       # Product browsing
│   │   ├── CartController.php          # Shopping cart
│   │   ├── CheckoutController.php      # Order creation
│   │   ├── OrderController.php         # Order management & verification
│   │   └── DashboardController.php     # Dashboard views
│   ├── Middleware/
│   │   └── EnsureRole.php              # Role-based access control
│   └── ...
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Medicine.php
│   ├── Order.php
│   └── OrderDetail.php
└── ...

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php        (updated with role)
│   ├── 2025_01_11_000100_create_categories_table.php
│   ├── 2025_01_11_000200_create_medicines_table.php
│   ├── 2025_01_11_000300_create_orders_table.php
│   └── 2025_01_11_000400_create_order_details_table.php
└── seeders/
    ├── UserSeeder.php
    ├── CategorySeeder.php
    ├── MedicineSeeder.php
    └── DatabaseSeeder.php

routes/
└── web.php                              # All application routes with role protection

storage/
├── app/
│   ├── medicines/                       # Medicine images
│   └── prescriptions/                   # Prescription files
└── ...
```

---

## API Routes Overview

### Public Routes
```
GET  /                          → Catalog home page
GET  /medicines                 → Browse medicines
GET  /medicines/{id}            → Medicine details
GET  /cart                      → View cart
POST /cart/add                  → Add to cart
POST /cart/update/{id}          → Update quantity
POST /cart/remove/{id}          → Remove from cart
POST /cart/clear                → Clear entire cart
```

### Patient Routes (Protected)
```
GET  /checkout                  → Checkout form
POST /checkout/process          → Submit order
GET  /orders/{id}               → View order details
GET  /my-orders                 → Order history
GET  /dashboard                 → Patient dashboard
```

### Pharmacist Routes (Protected)
```
GET  /pharmacist/dashboard      → Pharmacist dashboard
GET  /pharmacist/pending-orders → List pending orders
POST /pharmacist/orders/{id}/verify → Verify order & reduce stock
POST /pharmacist/orders/{id}/status → Update order status
GET  /pharmacist/low-stock      → Low stock alerts
```

### Admin Routes (Protected)
```
GET  /admin/dashboard           → Admin dashboard
GET  /medicines                 → List medicines
POST /medicines                 → Create medicine
GET  /medicines/{id}/edit       → Edit form
PUT  /medicines/{id}            → Update medicine
DELETE /medicines/{id}          → Delete medicine
```

---

## Business Rules Implemented

1. **Stock Management**:
   - Stock decreases only when pharmacist verifies an order
   - Validation prevents orders exceeding available stock

2. **Prescription Requirement**:
   - Medicines with `needs_recipe = true` require prescription upload
   - Prescription file stored during checkout
   - Validation enforces file upload if any item requires recipe

3. **Order Status Flow**:
   - `pending` → Patient placed order, awaiting pharmacist review
   - `verified` → Pharmacist confirmed, stock reduced
   - `shipped` → Order in transit
   - `completed` → Order delivered

4. **Low Stock Alerts**:
   - Medicines with stock < 10 shown in pharmacist dashboard
   - Pharmacist can quickly identify reordering needs

---

## Development Tips

### Running Tests
```bash
php artisan test
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Reset (Caution!)
```bash
php artisan migrate:fresh --seed
```

### Generate New Model + Migration
```bash
php artisan make:model ModelName -m
```

### Generate New Controller
```bash
php artisan make:controller ControllerName
```

---

## Troubleshooting

**Issue**: Storage images not accessible
- Run: `php artisan storage:link`

**Issue**: Database connection error
- Check `.env` database credentials
- Ensure MySQL is running
- Database `MedicStore` exists

**Issue**: Middleware 403 Forbidden error
- Check user role in database
- Verify route middleware configuration
- Clear config cache: `php artisan config:clear`

**Issue**: File upload fails
- Check `storage/app/public` directory exists
- Verify storage disk is set to `public` in config/filesystems.php

---

## Next Steps (Views & Templates)

The controllers are ready. You'll need to create Blade templates for:
- **Catalog views**: `resources/views/catalog/`
- **Cart views**: `resources/views/cart/`
- **Checkout views**: `resources/views/checkout/`
- **Medicine management**: `resources/views/medicines/`
- **Dashboard views**: `resources/views/dashboard/`
- **Order management**: `resources/views/orders/`

All templates should use **Tailwind CSS** with medical-themed colors (Blue/Green/White).

---

## Support
For questions or issues, refer to:
- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- MySQL: https://dev.mysql.com/doc/
