<?php

namespace App\Repositories\Contracts;

interface CartRepositoryInterface
{
    public function getByUserId(string|int $userId);
    
    public function updateCart(string|int $userId, array $items);
    
    public function clearCart(string|int $userId);
}
