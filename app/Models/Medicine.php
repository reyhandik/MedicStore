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
