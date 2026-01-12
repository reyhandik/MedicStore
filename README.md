# 🏥 MedicStore - Online Pharmacy System

**Sistem Apotek Online Sederhana** (Simple Online Pharmacy System) built with Laravel 11, Tailwind CSS, and MySQL.

## 📋 Overview

MedicStore is a full-featured web application for managing an online pharmacy with:
- **Patient Portal**: Browse medicines, cart, checkout with prescription upload
- **Pharmacist Dashboard**: Order verification, stock management, low-stock alerts
- **Admin Dashboard**: Sales analytics, user management, medicine CRUD
- **Role-Based Access Control**: Admin, Pharmacist, Patient roles with protected routes

## ✨ Key Features

### 🛍️ Patient Features
- Browse medicine catalog with search and category filtering
- Add medicines to shopping cart
- Upload prescription for medicines requiring recipes
- Checkout with order creation
- View order history and tracking
- Personal dashboard with order statistics

### 💊 Pharmacist Features
- Verify pending orders
- Update order status (verified → shipped → completed)
- Automatic stock reduction on order verification
- View low-stock alerts (< 10 items)
- Manage medicines (CRUD operations)
- Dashboard with order analytics

### 👨‍💼 Admin Features
- Full medicine management with image uploads
- Sales reports and analytics
- User management
- View all system orders
- Admin dashboard with key metrics

## 🛠️ Tech Stack

- **Backend**: PHP Laravel 11
- **Database**: MySQL 8.0+
- **Frontend**: Tailwind CSS, Blade Templates
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage (public disk)
- **Build Tool**: Vite

## 📦 System Requirements

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js 18+
- npm

## ⚡ Quick Start (5 Minutes)

### Step 1: Install Dependencies
```bash
cd c:\laragon\www\MedicStore
composer install
npm install
```

### Step 2: Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### Step 3: Database Setup
```bash
# Update .env with your database credentials:
# DB_DATABASE=MedicStore
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seed data
php artisan migrate
php artisan db:seed
```

### Step 4: File Storage
```bash
php artisan storage:link
```

### Step 5: Start Server
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Build assets (optional but recommended)
npm run dev
```

Access at: **http://localhost:8000**

## 👤 Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@medicstore.com | password |
| Pharmacist | pharmacist@medicstore.com | password |
| Patient | john@example.com | password |
| Patient | jane@example.com | password |

## 📚 Documentation

- **[QUICKSTART.md](./QUICKSTART.md)** - 5-minute getting started guide
- **[SETUP.md](./SETUP.md)** - Detailed setup instructions
- **[COMMANDS.md](./COMMANDS.md)** - Terminal commands reference
- **[ARCHITECTURE.md](./ARCHITECTURE.md)** - Detailed system architecture
- **[MODELS_MIGRATIONS.md](./MODELS_MIGRATIONS.md)** - Database schema & models
- **[IMPLEMENTATION.md](./IMPLEMENTATION.md)** - Implementation summary

## 🗄️ Database Schema

### Tables
- **users**: User accounts with roles (admin, pharmacist, patient)
- **categories**: Medicine categories
- **medicines**: Medicine inventory with pricing and recipe flags
- **orders**: Customer orders with status tracking
- **order_details**: Line items for each order

## 🔑 Key Models & Relationships

```php
User hasMany Orders
Category hasMany Medicines
Medicine belongsTo Category
Medicine hasMany OrderDetails
Order belongsTo User
Order hasMany OrderDetails
OrderDetail belongsTo Order, Medicine
```

## 🛣️ API Routes

### Public Routes
```
GET    /                    Catalog home
GET    /medicines           Browse medicines
GET    /medicines/{id}      Medicine details
GET    /cart                View cart
POST   /cart/add            Add to cart
```

### Patient Routes (Protected)
```
GET    /checkout            Checkout form
POST   /checkout/process    Submit order
GET    /orders/{id}         Order details
GET    /my-orders           Order history
GET    /dashboard           Patient dashboard
```

### Pharmacist Routes (Protected)
```
GET    /pharmacist/dashboard      Dashboard
GET    /pharmacist/pending-orders Pending orders
POST   /pharmacist/orders/{id}/verify Verify order
```

### Admin Routes (Protected)
```
GET    /admin/dashboard     Dashboard
GET    /medicines           List medicines
POST   /medicines           Create
PUT    /medicines/{id}      Update
DELETE /medicines/{id}      Delete
```

## 💼 Business Logic

### Stock Management
- ✅ Validates stock before adding to cart
- ✅ Validates again at checkout
- ✅ Automatically reduces stock when order is verified by pharmacist

### Prescription Requirements
- ✅ System detects if any medicine requires prescription
- ✅ Enforces file upload during checkout if needed
- ✅ Stores prescription file for verification

### Order Workflow
```
PENDING (Created by patient)
   ↓
VERIFIED (Confirmed by pharmacist, stock reduced)
   ↓
SHIPPED (In transit)
   ↓
COMPLETED (Delivered)
```

## 🔐 Security Features

- Role-Based Access Control (RBAC) middleware
- Password hashing with Laravel's built-in encryption
- CSRF protection
- SQL injection protection via Eloquent ORM
- File upload validation (mime types, file size)
- Authorization checks at controller and route levels

## 📁 File Uploads

- **Medicine Images**: `storage/app/public/medicines/`
  - Accessible: `/storage/medicines/{filename}`
- **Prescriptions**: `storage/app/public/prescriptions/`
  - Accessible: `/storage/prescriptions/{filename}`

## 🧪 Testing the Application

### Test Patient Flow
1. Login as john@example.com (password)
2. Browse medicines on home page
3. Search and filter by category
4. Add Paracetamol (no recipe needed) to cart
5. Go to `/cart`
6. Checkout without prescription file
7. View order at `/my-orders`

### Test Pharmacist Flow
1. Login as pharmacist@medicstore.com (password)
2. Go to `/pharmacist/pending-orders`
3. Verify the order created above
4. Check `/pharmacist/low-stock` for alerts
5. View dashboard at `/pharmacist/dashboard`

### Test Admin Flow
1. Login as admin@medicstore.com (password)
2. Go to `/admin/dashboard` for analytics
3. Create new medicine at `/medicines/create`
4. Upload image and set pricing
5. Edit/delete medicines

## 📊 Dashboard Features

### Admin Dashboard
- Total sales (verified orders)
- User count
- Medicine count
- Recent orders
- Top 5 selling medicines

### Pharmacist Dashboard
- Order counts by status
- Low-stock medicines (< 10)
- Recent pending orders
- Quick verification access

### Patient Dashboard
- Recent orders
- Total orders count
- Total spent amount
- Quick order status check

## 🚀 Production Deployment

```bash
# Install dependencies (no dev)
composer install --no-dev

# Optimize for production
php artisan config:cache
php artisan optimize

# Build frontend
npm run build

# Run migrations
php artisan migrate --force

# Clear cache
php artisan cache:clear
```

## 🐛 Troubleshooting

### Database Connection Error
- Check `.env` database credentials
- Ensure MySQL is running
- Verify database `MedicStore` exists

### Storage Images Not Accessible
```bash
php artisan storage:link
```

### Middleware 403 Error
```bash
php artisan config:clear
php artisan route:clear
```

## 📝 Development Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run tinker shell
php artisan tinker

# List all routes
php artisan route:list

# Generate model + migration
php artisan make:model ModelName -m

# Run tests
php artisan test
```

## 📖 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)
- [Tailwind CSS](https://tailwindcss.com)
- [Laravel Breeze](https://laravel.com/docs/starter-kits#breeze)

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Contributing

This is a project for educational purposes. Feel free to fork and improve!

## 📧 Support

For issues or questions, refer to:
- Laravel Documentation
- Project documentation in this repository
- GitHub Issues

---

**Status**: ✅ Backend Complete  
**Framework**: Laravel 11  
**Database**: MySQL  
**Frontend**: Tailwind CSS + Blade  
**Last Updated**: January 11, 2026

Happy coding! 🚀


We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#   M e d i c S t o r e  
 