# MedicStore - Models & Migrations Complete Reference

## Complete Models Code

### User Model (app/Models/User.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all orders for this user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Helper method to check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper method to check if user is pharmacist.
     */
    public function isPharmacist(): bool
    {
        return $this->role === 'pharmacist';
    }

    /**
     * Helper method to check if user is patient.
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }
}
```

### Category Model (app/Models/Category.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get all medicines in this category.
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
```

### Medicine Model (app/Models/Medicine.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'needs_recipe',
    ];

    protected function casts(): array
    {
        return [
            'needs_recipe' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the category this medicine belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all order details for this medicine.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
```

### Order Model (app/Models/Order.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'total_price',
        'recipe_file',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the user who placed this order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all order details for this order.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Check if order requires recipe verification.
     */
    public function requiresRecipe(): bool
    {
        return $this->orderDetails()->whereHas('medicine', function ($query) {
            $query->where('needs_recipe', true);
        })->exists();
    }
}
```

### OrderDetail Model (app/Models/OrderDetail.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'medicine_id',
        'qty',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
        ];
    }

    /**
     * Get the order this detail belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the medicine in this order detail.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
```

---

## Complete Migrations Code

### Users Migration (Modified)
**File**: `database/migrations/0001_01_01_000000_create_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'pharmacist', 'patient'])->default('patient');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

### Categories Migration
**File**: `database/migrations/2025_01_11_000100_create_categories_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

### Medicines Migration
**File**: `database/migrations/2025_01_11_000200_create_medicines_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('needs_recipe')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
```

### Orders Migration
**File**: `database/migrations/2025_01_11_000300_create_orders_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_price', 12, 2);
            $table->string('recipe_file')->nullable();
            $table->enum('status', ['pending', 'verified', 'shipped', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

### OrderDetails Migration
**File**: `database/migrations/2025_01_11_000400_create_order_details_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
```

---

## Model Relationships Diagram

```
User (1) ──────────→ (Many) Order
         hasMany         belongsTo
         ↓
    orders()      ←      user()

Category (1) ────────→ (Many) Medicine
            hasMany        belongsTo
            ↓
       medicines()    ←    category()

Order (1) ──────────→ (Many) OrderDetail
      hasMany             belongsTo
      ↓
  orderDetails()    ←    order()

Medicine (1) ─────→ (Many) OrderDetail
          hasMany       belongsTo
          ↓
    orderDetails()  ←   medicine()
```

---

## Query Examples

```php
// Get user with all their orders and order details
$user = User::with(['orders.orderDetails.medicine'])->find(1);

// Get order with all details and their medicines
$order = Order::with('orderDetails.medicine')->find(1);

// Get all medicines requiring prescription
$recipeMedicines = Medicine::where('needs_recipe', true)->get();

// Get low-stock medicines
$lowStock = Medicine::where('stock', '<', 10)->with('category')->get();

// Get pending orders with user and medicine details
$pending = Order::where('status', 'pending')
    ->with(['user', 'orderDetails.medicine'])
    ->latest('order_date')
    ->get();

// Get top 5 selling medicines
$topSelling = Medicine::join('order_details', 'medicines.id', '=', 'order_details.medicine_id')
    ->select('medicines.*', \DB::raw('SUM(order_details.qty) as total_sold'))
    ->groupBy('medicines.id')
    ->orderBy('total_sold', 'desc')
    ->limit(5)
    ->get();

// Get total sales from verified orders
$totalSales = Order::where('status', 'verified')->sum('total_price');

// Get orders by user
$userOrders = User::find(1)->orders()->latest()->get();

// Get order details with prices
$orderDetails = OrderDetail::with('medicine:id,name,price')->where('order_id', 1)->get();

// Get medicines in a specific category
$categoryMedicines = Category::find(1)->medicines()->paginate(15);
```

---

## Tinker Commands for Testing

```php
# Create sample data
php artisan tinker

# Users
$user = App\Models\User::find(1);
$user->orders->count();

# Categories
$cat = App\Models\Category::create(['name' => 'Test']);
$cat->medicines()->create(['name' => 'Test Med', 'price' => 10000, 'stock' => 50]);

# Orders
$order = App\Models\Order::where('status', 'pending')->first();
$order->user->name;
$order->total_price;

# OrderDetails
$order->orderDetails()->with('medicine')->get();

# Medicines
$med = App\Models\Medicine::find(1);
$med->stock;
$med->decrement('stock', 5);  // Reduce stock by 5

# Statistics
App\Models\Order::where('status', 'verified')->count();
App\Models\Medicine::where('stock', '<', 10)->count();
```

---

## Casting & Type Hints

All models use proper type casting:

- **Booleans**: `needs_recipe` ✓
- **Decimals**: `price`, `total_price`, `subtotal` ✓
- **Dates**: `order_date`, `created_at`, `updated_at` ✓
- **Passwords**: Hashed in User model ✓

This ensures type safety and proper formatting in queries and API responses.

---

## Relationships Summary

| Model | Relationship | Method |
|-------|-------------|--------|
| User | has many Orders | `$user->orders` |
| Category | has many Medicines | `$category->medicines` |
| Medicine | belongs to Category | `$medicine->category` |
| Medicine | has many OrderDetails | `$medicine->orderDetails` |
| Order | belongs to User | `$order->user` |
| Order | has many OrderDetails | `$order->orderDetails` |
| OrderDetail | belongs to Order | `$detail->order` |
| OrderDetail | belongs to Medicine | `$detail->medicine` |

---

Complete backend implementation ready for views! ✨
