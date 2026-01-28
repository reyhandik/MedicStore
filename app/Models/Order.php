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
        'customer_name',
        'customer_phone',
        'delivery_address',
        'delivery_city',
        'delivery_postal_code',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'notes',
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
