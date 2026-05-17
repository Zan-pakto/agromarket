<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function find(string|int $id);
    
    public function create(array $data);
    
    public function updateStatus(string|int $id, string $status);
    
    public function getByUser(string|int $userId, int $perPage = 10);
    
    public function getBySeller(string|int $sellerId, int $perPage = 10);
    
    public function getAll(int $perPage = 15);
    
    public function getAnalytics();
}
