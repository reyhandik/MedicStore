# MedicStore - Code Architecture & Reference

## 1. Database Migrations

### Users Table (Modified)
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255),
  role ENUM('admin', 'pharmacist', 'patient') DEFAULT 'patient',
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Categories Table
```sql
CREATE TABLE categories (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255) UNIQUE,
  description TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Medicines Table
```sql
CREATE TABLE medicines (
  id BIGINT PRIMARY KEY,
  category_id BIGINT (FK -> categories),
  name VARCHAR(255),
  description TEXT NULL,
  price DECIMAL(10,2),
  stock INT DEFAULT 0,
  image VARCHAR(255) NULL,
  needs_recipe BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Orders Table
```sql
CREATE TABLE orders (
  id BIGINT PRIMARY KEY,
  user_id BIGINT (FK -> users),
  order_date TIMESTAMP,
  total_price DECIMAL(12,2),
  recipe_file VARCHAR(255) NULL,
  status ENUM('pending', 'verified', 'shipped', 'completed') DEFAULT 'pending',
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Order Details Table
```sql
CREATE TABLE order_details (
  id BIGINT PRIMARY KEY,
  order_id BIGINT (FK -> orders),
  medicine_id BIGINT (FK -> medicines),
  qty INT,
  subtotal DECIMAL(12,2),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

---

## 2. Eloquent Models & Relationships

### User Model
```php
// app/Models/User.php
class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password', 'role'];
    
    // Relationships
    public function orders() {
        return $this->hasMany(Order::class);
    }
    
    // Helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isPharmacist(): bool { return $this->role === 'pharmacist'; }
    public function isPatient(): bool { return $this->role === 'patient'; }
}
```

### Category Model
```php
// app/Models/Category.php
class Category extends Model {
    protected $fillable = ['name', 'description'];
    
    public function medicines() {
        return $this->hasMany(Medicine::class);
    }
}
```

### Medicine Model
```php
// app/Models/Medicine.php
class Medicine extends Model {
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 
        'stock', 'image', 'needs_recipe'
    ];
    protected $casts = [
        'needs_recipe' => 'boolean',
        'price' => 'decimal:2',
    ];
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }
}
```

### Order Model
```php
// app/Models/Order.php
class Order extends Model {
    protected $fillable = [
        'user_id', 'order_date', 'total_price', 'recipe_file', 'status'
    ];
    protected $casts = [
        'order_date' => 'datetime',
        'total_price' => 'decimal:2',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }
    
    public function requiresRecipe(): bool {
        return $this->orderDetails()->whereHas('medicine', 
            fn($q) => $q->where('needs_recipe', true)
        )->exists();
    }
}
```

### OrderDetail Model
```php
// app/Models/OrderDetail.php
class OrderDetail extends Model {
    protected $fillable = [
        'order_id', 'medicine_id', 'qty', 'subtotal'
    ];
    protected $casts = ['subtotal' => 'decimal:2'];
    
    public function order() {
        return $this->belongsTo(Order::class);
    }
    
    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }
}
```

---

## 3. Middleware: Role-Based Access Control

### EnsureRole Middleware
```php
// app/Http/Middleware/EnsureRole.php
class EnsureRole {
    public function handle(Request $request, Closure $next, ...$roles) {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
}
```

**Usage in Routes**:
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Only admins
});

Route::middleware(['auth', 'role:pharmacist,admin'])->group(function () {
    // Pharmacists and Admins
});

Route::middleware(['auth', 'role:patient'])->group(function () {
    // Only patients
});
```

---

## 4. Controllers

### MedicineController - CRUD Operations
**Methods**:
- `index()` - List medicines (paginated, 15 per page)
- `create()` - Show create form
- `store(Request $request)` - Save new medicine + image upload
- `show(Medicine $medicine)` - View details
- `edit(Medicine $medicine)` - Show edit form
- `update(Request $request, Medicine $medicine)` - Update + image replacement
- `destroy(Medicine $medicine)` - Delete + cleanup old image

**Features**:
- Image upload to `storage/app/public/medicines/`
- Automatic old image deletion on update
- Validation: price, stock, file size, mime types

---

### CatalogController - Product Browsing
**Methods**:
- `index(Request $request)` - List medicines with filters
  - Filter by category_id
  - Search by name or description
  - Pagination (12 per page)
- `show(Medicine $medicine)` - Display medicine details

---

### CartController - Shopping Cart (Session-based)
**Methods**:
- `index()` - Display cart with total and recipe requirement check
- `add(Request $request)` - Add medicine to cart
  - Validates stock availability
  - Merges quantity if item exists
  - Recalculates subtotal
- `update(Request $request, $medicineId)` - Update quantity
- `remove($medicineId)` - Remove item
- `clear()` - Clear entire cart

**Session Key**: `cart` (array of items with id, name, price, qty, subtotal)

---

### CheckoutController - Order Creation
**Methods**:
- `show()` - Display checkout form
  - Detects if prescription required
  - Shows total price
  - Validates cart not empty
- `process(Request $request)` - Create order
  - Validates prescription file if required
  - Checks stock again
  - Creates Order record (status: pending)
  - Creates OrderDetail records
  - Stores prescription to `storage/app/public/prescriptions/`
  - Clears cart session

---

### OrderController - Order Management & Verification
**Methods**:
- `show(Order $order)` - View order details
  - Only owner or pharmacist/admin
- `patientOrders()` - Patient's order history
- `pendingOrders()` - Pharmacist: List pending orders
- `verify(Order $order)` - Pharmacist: Verify & reduce stock
  - Validates stock available
  - Sets status to "verified"
  - Decrements stock for each item
- `updateStatus(Request $request, Order $order)` - Update status
- `lowStockAlerts()` - Pharmacist: View medicines with stock < 10

---

### DashboardController - Analytics & Overview
**Methods**:
- `admin()` - Admin dashboard
  - Total sales (verified orders)
  - Total orders, users, medicines count
  - Recent 5 orders
  - Top 5 selling medicines
  
- `pharmacist()` - Pharmacist dashboard
  - Order counts by status
  - Low stock medicines
  - Recent 5 pending orders
  
- `patient()` - Patient dashboard
  - User's recent orders
  - Total orders count
  - Total spent amount

---

## 5. Routes & Access Control

### Public Routes
```
GET  /                          Catalog home
GET  /medicines                 Browse medicines
GET  /medicines/{id}            Medicine details
GET  /cart                      View cart
POST /cart/add                  Add to cart
POST /cart/update/{id}          Update quantity
POST /cart/remove/{id}          Remove from cart
POST /cart/clear                Clear cart
```

### Patient Routes (auth + role:patient)
```
GET  /checkout                  Checkout form
POST /checkout/process          Submit order
GET  /orders/{id}               Order details
GET  /my-orders                 Order history
GET  /dashboard                 Patient dashboard
```

### Pharmacist Routes (auth + role:pharmacist)
```
GET  /pharmacist/dashboard      Pharmacist dashboard
GET  /pharmacist/pending-orders Pending orders
POST /pharmacist/orders/{id}/verify Verify & reduce stock
POST /pharmacist/orders/{id}/status Update order status
GET  /pharmacist/low-stock      Low stock alerts
GET  /medicines                 List medicines
POST /medicines                 Create medicine
GET  /medicines/{id}/edit       Edit form
PUT  /medicines/{id}            Update medicine
DELETE /medicines/{id}          Delete medicine
```

### Admin Routes (auth + role:admin)
```
GET  /admin/dashboard           Admin dashboard
GET  /medicines                 List medicines
POST /medicines                 Create medicine
GET  /medicines/{id}/edit       Edit form
PUT  /medicines/{id}            Update medicine
DELETE /medicines/{id}          Delete medicine
```

---

## 6. Seeders (Sample Data)

### UserSeeder - Test Accounts
```php
Admin:       admin@medicstore.com / password
Pharmacist:  pharmacist@medicstore.com / password
Patient 1:   john@example.com / password
Patient 2:   jane@example.com / password
```

### CategorySeeder - 6 Categories
- Pain Relief
- Cold & Flu
- Antibiotics
- Vitamins
- Skin Care
- Digestive

### MedicineSeeder - 10 Sample Medicines
Includes various pricing (8,000 - 55,000 IDR) and stock levels, with some requiring prescriptions (Antibiotics).

---

## 7. File Storage Paths

**Medicine Images**: `storage/app/public/medicines/{filename}`
- Accessible: `http://localhost:8000/storage/medicines/{filename}`

**Prescription Files**: `storage/app/public/prescriptions/{filename}`
- Accessible: `http://localhost:8000/storage/prescriptions/{filename}`

---

## 8. Business Logic Summary

### Stock Management
1. Stock validates BEFORE adding to cart
2. Stock validates AGAIN at checkout
3. Stock ONLY decreases when pharmacist verifies order

### Prescription Requirements
1. During checkout, system detects if any item needs recipe
2. If yes, file upload is REQUIRED
3. File stored in order record for verification

### Order Status Flow
```
PENDING (created)
    ↓ (Pharmacist verifies)
VERIFIED (stock reduced)
    ↓ (Manual update)
SHIPPED
    ↓ (Manual update)
COMPLETED
```

### Authorization
- Users can only access their own orders
- Pharmacists/Admins can access any order
- Only authenticated users can checkout
- Role middleware prevents unauthorized access

---

## 9. Validation Rules

### Create/Update Medicine
```php
category_id: required|exists:categories,id
name: required|string|max:255
description: nullable|string
price: required|numeric|min:0
stock: required|integer|min:0
image: nullable|image|mimes:jpeg,png,jpg,gif|max:2048
needs_recipe: boolean
```

### Add to Cart
```php
medicine_id: required|exists:medicines,id
qty: required|integer|min:1
```

### Checkout (if recipe required)
```php
recipe_file: required|file|mimes:pdf,jpeg,png,jpg|max:5120
```

---

## 10. Key Design Decisions

1. **Session-based Cart** - No database persistence needed for temporary items
2. **Stock Validation Multiple Times** - Prevents race conditions
3. **Automatic Stock Reduction** - Only on verification, not on order creation
4. **Separate OrderDetails Table** - Allows tracking line items independently
5. **Role-based Middleware** - Simple, efficient authorization
6. **Public Storage** - Allows direct image/PDF access via HTTP
7. **Seeded Test Data** - Enables immediate testing without manual setup

---

## 11. Example API Usage (via Tinker)

```php
# Create a category
$cat = App\Models\Category::create(['name' => 'Pain Relief']);

# Create a medicine
$med = App\Models\Medicine::create([
    'category_id' => 1,
    'name' => 'Paracetamol',
    'price' => 15000,
    'stock' => 100,
    'needs_recipe' => false,
]);

# Find and update medicine
$med = App\Models\Medicine::find(1);
$med->update(['stock' => 50]);

# Get user's orders
$user = App\Models\User::find(2);
$user->orders()->get();

# Get medicines needing prescription
App\Models\Medicine::where('needs_recipe', true)->get();

# Get pending orders
App\Models\Order::where('status', 'pending')->get();

# Count low stock items
App\Models\Medicine::where('stock', '<', 10)->count();
```

---

This completes the backend architecture. Next step: Create Blade templates for views!
