<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 12)
    {
        $query = Product::query()->where('is_available', true);

        // Advanced Search & Filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (!empty($filters['rating'])) {
            $query->where('ratings_avg', '>=', (float) $filters['rating']);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('ratings_avg', 'desc')->orderBy('ratings_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($perPage)->withQueryString();
    }
    
    public function find(string|int $id)
    {
        return Product::findOrFail($id);
    }
    
    public function create(array $data)
    {
        return Product::create($data);
    }
    
    public function update(string|int $id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }
    
    public function delete(string|int $id)
    {
        $product = $this->find($id);
        return $product->delete();
    }
    
    public function getFeatured(int $limit = 8)
    {
        return Product::where('is_available', true)
            ->orderBy('ratings_avg', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    public function getRelated(string|int $id, int $limit = 4)
    {
        $product = $this->find($id);
        return Product::where('is_available', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit($limit)
            ->get();
    }
    
    public function getBySeller(string|int $sellerId, int $perPage = 10)
    {
        return Product::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
