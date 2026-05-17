<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array', // Array format: [['product_id' => 1, 'quantity' => 2, 'price' => 15.00]]
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
