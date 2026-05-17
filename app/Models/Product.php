<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category_id',
        'seller_id',
        'images',
        'ratings_avg',
        'ratings_count',
        'is_available',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'float',
        'quantity' => 'integer',
        'ratings_avg' => 'float',
        'ratings_count' => 'integer',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    // Helper for gallery
    public function getFirstImage(): string
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        return 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=500&auto=format&fit=crop'; // Default high-quality placeholder
    }

    public function getStockStatus(): string
    {
        if (!$this->is_available || $this->quantity <= 0) {
            return 'Out of Stock';
        }
        if ($this->quantity < 10) {
            return 'Low Stock (' . $this->quantity . ' left)';
        }
        return 'In Stock';
    }
}
