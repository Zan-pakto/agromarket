<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function getByUserId(string|int $userId)
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId],
            ['items' => []]
        );
    }
    
    public function updateCart(string|int $userId, array $items)
    {
        $cart = $this->getByUserId($userId);
        $cart->items = $items;
        $cart->save();
        return $cart;
    }
    
    public function clearCart(string|int $userId)
    {
        $cart = $this->getByUserId($userId);
        $cart->items = [];
        $cart->save();
        return $cart;
    }
}
