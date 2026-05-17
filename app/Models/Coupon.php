<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type', // fixed, percent
        'value',
        'min_spend',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'float',
        'min_spend' => 'float',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid(float $subtotal): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($subtotal < $this->min_spend) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid($subtotal)) {
            return 0.00;
        }

        if ($this->type === 'percent') {
            return round(($this->value / 100) * $subtotal, 2);
        }

        return min($this->value, $subtotal); // fixed discount cannot exceed subtotal
    }
}
