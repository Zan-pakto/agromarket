<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'items',
        'total_amount',
        'discount_amount',
        'coupon_code',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'payment_method',
        'payment_status',
        'order_status',
        'tracking_number',
    ];

    protected $casts = [
        'items' => 'array', // Array format: [['product_id' => 1, 'name' => 'Tomato Seeds', 'quantity' => 2, 'price' => 15.00]]
        'total_amount' => 'float',
        'discount_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusColorClass(): string
    {
        return match ($this->order_status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-900/50',
            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-900/50',
            'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-900/50',
            'delivered' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-900/50',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-900/50',
            default => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-400',
        };
    }
}
