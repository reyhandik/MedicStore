# MedicStore - Quick Start (5 Minutes)

## TL;DR - Get Running NOW

### 1. Open PowerShell and navigate to project:
```powershell
cd c:\laragon\www\MedicStore
```

### 2. Run initial setup (one time):
```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 3. Start the server:
```powershell
php artisan serve
```

### 4. In another PowerShell window (for live CSS reloading):
```powershell
cd c:\laragon\www\MedicStore
npm run dev
```

### 5. Access the app:
```
http://localhost:8000
```

---

## Login & Test

| Role | Email | Password | Purpose |
|------|-------|----------|---------|
| Admin | admin@medicstore.com | password | Manage all medicines, view sales reports |
| Pharmacist | pharmacist@medicstore.com | password | Verify orders, update stock, check low-stock |
| Patient | john@example.com | password | Browse, cart, checkout, order history |
| Patient | jane@example.com | password | Same as above |

---

## Feature Walkthrough

### As a Patient:
1. Browse medicines at `/`
2. Search/filter by category
3. Add items to cart
4. Go to `/cart` and review
5. Click "Checkout"
6. Upload prescription if any item requires it
7. Submit order
8. View order at `/my-orders`

### As a Pharmacist:
1. Go to `/pharmacist/dashboard`
2. See order counts and low-stock alerts
3. Click "Pending Orders" 
4. Verify an order (stock decreases automatically)
5. Check `/pharmacist/low-stock` for medicines to reorder

### As an Admin:
1. Go to `/admin/dashboard`
2. View sales analytics, user count, top medicines
3. Go to `/medicines` to manage inventory
4. Create/edit/delete medicines with images

---

## What's Already Done

✅ Database migrations & schema  
✅ Eloquent models with relationships  
✅ Role-based middleware (RBAC)  
✅ All controllers (Cart, Checkout, Orders, Catalog, Medicine CRUD)  
✅ Session-based shopping cart  
✅ Prescription validation & file upload  
✅ Stock management (decreases on verification)  
✅ Order status workflow  
✅ Low-stock alerts  
✅ Dashboards for all roles  
✅ Sample data seeders  
✅ All routes configured

---

## What You Need to Do

Create Blade templates (views) for:

### Layouts
- `resources/views/layouts/app.blade.php` - Main layout with nav
- `resources/views/layouts/guest.blade.php` - Login/register layout (from Breeze)

### Catalog/Shopping
- `resources/views/catalog/index.blade.php` - Medicine list with filters
- `resources/views/catalog/show.blade.php` - Medicine details
- `resources/views/cart/index.blade.php` - Shopping cart
- `resources/views/checkout/show.blade.php` - Checkout form

### Medicine Management
- `resources/views/medicines/index.blade.php` - Medicine list (admin/pharmacist)
- `resources/views/medicines/create.blade.php` - Create form
- `resources/views/medicines/edit.blade.php` - Edit form
- `resources/views/medicines/show.blade.php` - View details

### Orders
- `resources/views/orders/show.blade.php` - Order details
- `resources/views/orders/patient-list.blade.php` - Patient's orders
- `resources/views/orders/pending-list.blade.php` - Pharmacist's pending orders
- `resources/views/orders/low-stock.blade.php` - Low stock medicines

### Dashboards
- `resources/views/dashboard/admin.blade.php` - Admin dashboard
- `resources/views/dashboard/pharmacist.blade.php` - Pharmacist dashboard
- `resources/views/dashboard/patient.blade.php` - Patient dashboard

---

## Testing Key Features

### Test Cart Functionality
```powershell
# Start dev server
php artisan serve

# In browser:
# 1. Go to http://localhost:8000
# 2. Click "Add to Cart" on a medicine
# 3. Go to /cart
# 4. Test update quantity, remove item
```

### Test Order Creation
```
# Login as patient (john@example.com / password)
# Add 1x Aspirin (no recipe needed)
# Go to checkout
# Submit without prescription (should work)
# Order created as pending
```

### Test Prescription Requirement
```
# Login as patient
# Add Amoxicillin (requires recipe) to cart
# Go to checkout
# Try submit without file (should fail)
# Upload PDF, then submit
# Order created with prescription file
```

### Test Stock Reduction
```
# Check medicine stock in database:
php artisan tinker
>>> App\Models\Medicine::find(4)->stock

# Login as pharmacist
# Verify the order above
# Check stock again (should be reduced)
>>> App\Models\Medicine::find(4)->stock
```

---

## Troubleshooting Quick Fixes

### "SQLSTATE[HY000] [1045]"
**Problem**: Database connection failed
```powershell
# Check .env has correct credentials
notepad .env

# Ensure MySQL is running in Laragon
# Create database:
# In MySQL: CREATE DATABASE MedicStore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### "Class not found" or 403 Unauthorized
**Problem**: Middleware/routing issues
```powershell
# Clear config cache
php artisan config:clear

# Check routes registered
php artisan route:list
```

### Images not displaying
**Problem**: Storage symlink missing
```powershell
php artisan storage:link
```

### CSS not applying
**Problem**: Frontend assets not compiled
```powershell
npm run build
# OR for development with watch:
npm run dev
```

---

## Database Structure Verification

```powershell
php artisan tinker

# Check users
>>> App\Models\User::all();

# Check medicines
>>> App\Models\Medicine::with('category')->first();

# Check order relationships
>>> $order = App\Models\Order::with('orderDetails.medicine')->first();
>>> $order->user->name;
>>> $order->orderDetails->count();
```

---

## Key File Locations

| Purpose | File Path |
|---------|-----------|
| Database Config | `.env` |
| Routes | `routes/web.php` |
| Controllers | `app/Http/Controllers/` |
| Models | `app/Models/` |
| Migrations | `database/migrations/` |
| Views | `resources/views/` |
| Images | `storage/app/public/medicines/` |
| Prescriptions | `storage/app/public/prescriptions/` |

---

## Next Steps

1. **Create views** - Start with `resources/views/catalog/index.blade.php` for the home page
2. **Use Tailwind CSS** - Apply medical theme (blue/green/white)
3. **Test all flows** - Use the testing guide above
4. **Add more seeders** - Generate more sample medicines for testing
5. **Customize styling** - Tailor to project brand

---

## Resources

- **Laravel Docs**: https://laravel.com/docs/11.x
- **Tailwind CSS**: https://tailwindcss.com
- **Blade Templating**: https://laravel.com/docs/11.x/blade
- **Eloquent ORM**: https://laravel.com/docs/11.x/eloquent

---

Good luck! The backend is production-ready. Now build amazing UX with Blade templates! 🚀
