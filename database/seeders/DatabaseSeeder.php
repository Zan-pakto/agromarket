<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test seller
        $seller = \App\Models\User::create([
            'name' => 'Demo Seller',
            'email' => 'seller@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'seller',
            'status' => 'approved',
            'phone' => '123-456-7890',
            'address' => '123 Farm Lane, Agrotown',
        ]);

        // Create categories
        $vegCat = \App\Models\Category::create([
            'name' => 'Vegetables',
            'slug' => 'vegetables',
            'description' => 'Fresh organic vegetables from the farm.',
            'icon' => 'fa-carrot',
        ]);

        $fruitCat = \App\Models\Category::create([
            'name' => 'Fruits',
            'slug' => 'fruits',
            'description' => 'Sweet and fresh fruits.',
            'icon' => 'fa-apple-alt',
        ]);

        $grainCat = \App\Models\Category::create([
            'name' => 'Grains',
            'slug' => 'grains',
            'description' => 'High quality grains and wheat.',
            'icon' => 'fa-seedling',
        ]);

        // Create Products
        \App\Models\Product::create([
            'name' => 'Organic Carrots',
            'description' => 'Freshly picked organic carrots, perfect for salads and cooking.',
            'price' => 3.50,
            'quantity' => 150,
            'category_id' => $vegCat->id,
            'seller_id' => $seller->id,
            'images' => json_encode(['/images/product_carrots.png']),
            'is_available' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'Juicy Apples',
            'description' => 'Sweet, crisp, and juicy red apples from our orchard.',
            'price' => 5.00,
            'quantity' => 200,
            'category_id' => $fruitCat->id,
            'seller_id' => $seller->id,
            'images' => json_encode(['/images/product_apples.png']),
            'is_available' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'Whole Wheat Grains',
            'description' => 'Premium whole wheat grains ready for milling.',
            'price' => 12.00,
            'quantity' => 50,
            'category_id' => $grainCat->id,
            'seller_id' => $seller->id,
            'images' => json_encode(['/images/product_grains.png']),
            'is_available' => true,
        ]);
        
        \App\Models\Product::create([
            'name' => 'Fresh Broccoli',
            'description' => 'Green and healthy broccoli crowns.',
            'price' => 4.20,
            'quantity' => 80,
            'category_id' => $vegCat->id,
            'seller_id' => $seller->id,
            'images' => json_encode(['/images/product_broccoli.png']),
            'is_available' => true,
        ]);
    }
}
