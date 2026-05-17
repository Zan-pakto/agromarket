<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 12);
    
    public function find(string|int $id);
    
    public function create(array $data);
    
    public function update(string|int $id, array $data);
    
    public function delete(string|int $id);
    
    public function getFeatured(int $limit = 8);
    
    public function getRelated(string|int $id, int $limit = 4);
    
    public function getBySeller(string|int $sellerId, int $perPage = 10);
}
