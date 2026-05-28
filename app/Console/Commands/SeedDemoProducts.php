<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SeedDemoProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agromarket:seed-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed standard agricultural categories, coupons, sellers, and seeds/machinery products for demonstration.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting AgroMarket Seed Command...');

        // 1. Seed Roles/Users
        $this->comment('Seeding default User Accounts (Admin, Seller, Farmer)...');
        
        $admin = User::updateOrCreate(
            ['email' => 'admin@agromarket.com'],
            [
                'name' => 'AgroMarket Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'approved',
                'phone' => '+1 (555) 019-2831',
                'address' => 'Global Agriculture Hub, Suite 101, Washington, DC',
            ]
        );

        $seller = User::updateOrCreate(
            ['email' => 'seller@agromarket.com'],
            [
                'name' => 'GreenValley Seeds Co.',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'status' => 'approved', // Pre-approved for instant testing
                'phone' => '+1 (555) 039-4488',
                'address' => 'Agricultural Industrial Park, Plot 42, Iowa',
            ]
        );

        $farmer = User::updateOrCreate(
            ['email' => 'farmer@agromarket.com'],
            [
                'name' => 'John Doe (Farmer)',
                'password' => Hash::make('password'),
                'role' => 'farmer',
                'status' => 'approved',
                'phone' => '+1 (555) 088-2911',
                'address' => 'Homestead Farms, Sector 5, Kansas',
            ]
        );

        // 2. Seed Categories
        $this->comment('Seeding Product Categories...');
        $categoriesData = [
            [
                'name' => 'Seeds',
                'description' => 'Premium high-germination hybrid and organic seeds for high yield vegetables, grains, and fruits.',
                'icon' => 'fa-seedling',
            ],
            [
                'name' => 'Fertilizers',
                'description' => 'Organic composts, slow-release nitrogen, potassium mixes, and micro-nutrient plant boosters.',
                'icon' => 'fa-leaf',
            ],
            [
                'name' => 'Farming Tools',
                'description' => 'Hand tools, professional pruning shears, ergonomic shovels, soil testers, and grafting items.',
                'icon' => 'fa-tools',
            ],
            [
                'name' => 'Irrigation Equipment',
                'description' => 'Drip emitters, micro-sprinklers, pressure regulators, digital water timers, and heavy-duty hoses.',
                'icon' => 'fa-tint',
            ],
            [
                'name' => 'Tractors & Machinery',
                'description' => 'Rototillers, walk-behind tractors, seed drills, power weeders, and specialized machinery attachments.',
                'icon' => 'fa-tractor',
            ],
            [
                'name' => 'Organic Products',
                'description' => '100% natural neem oils, bio-pesticides, organic worm castings, and chemical-free agricultural inputs.',
                'icon' => 'fa-apple-alt',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                ]
            );
        }

        // 3. Seed Coupons
        $this->comment('Seeding Promotional Coupons...');
        Coupon::updateOrCreate(
            ['code' => 'AGRO20'],
            [
                'type' => 'percent',
                'value' => 20.00,
                'min_spend' => 50.00,
                'expires_at' => now()->addDays(30),
                'is_active' => true,
            ]
        );
        Coupon::updateOrCreate(
            ['code' => 'HARVEST10'],
            [
                'type' => 'fixed',
                'value' => 10.00,
                'min_spend' => 30.00,
                'expires_at' => now()->addDays(15),
                'is_active' => true,
            ]
        );

        // 4. Seed Products
        $this->comment('Seeding Demonstration Products...');
        $productsData = [
            // Seeds
            [
                'name' => 'Organic Heirloom Tomato Seeds',
                'description' => 'Non-GMO organic Beefsteak Tomato seeds. High-germination rate (92%) and superb disease resistance. Perfect for family farms and home gardening alike. Each pack contains approximately 200 seeds.',
                'price' => 12.99,
                'quantity' => 150,
                'category_name' => 'Seeds',
                'images' => [
                    'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600&auto=format&fit=crop',
                ],
                'ratings_avg' => 4.8,
                'ratings_count' => 12,
            ],
            [
                'name' => 'Hybrid Sweet Corn Seeds (1kg)',
                'description' => 'High-yield F1 hybrid sweet corn seeds. Specifically bred for sugary-sweet kernels, uniform ear sizing, and rapid maturation. Suitable for diverse soil layouts.',
                'price' => 24.50,
                'quantity' => 80,
                'category_name' => 'Seeds',
                'images' => [
                    '/images/product_corn_seeds.png',
                ],
                'ratings_avg' => 4.5,
                'ratings_count' => 8,
            ],

            // Fertilizers
            [
                'name' => 'Premium Bio-Organic Compost (5kg)',
                'description' => 'Fully-decomposed natural cow dung and vermicompost compost mixture. Retains moisture, enriches sandy/clay soils with active nitrogen, and sparks helpful microbial action.',
                'price' => 18.00,
                'quantity' => 300,
                'category_name' => 'Fertilizers',
                'images' => [
                    '/images/product_compost.png',
                ],
                'ratings_avg' => 4.7,
                'ratings_count' => 15,
            ],
            [
                'name' => 'All-Purpose Liquid Plant Food NPK',
                'description' => 'Balanced 10-10-10 liquid fertilizer concentrate. Promotes immediate nutrient absorption in vegetables, leafy greens, and flowering crops. Easy to dilute and spray.',
                'price' => 14.25,
                'quantity' => 120,
                'category_name' => 'Fertilizers',
                'images' => [
                    'https://images.unsplash.com/photo-1574316071802-0d684efa7bf5?w=600&auto=format&fit=crop',
                ],
                'ratings_avg' => 4.2,
                'ratings_count' => 6,
            ],

            // Farming Tools
            [
                'name' => 'Professional Ergonomic Hand Trowel',
                'description' => 'Heavy-duty rustproof stainless steel trowel with a soft rubberized non-slip grip. Features depth-gauges on the scoop to make bulb and seed depth placement highly precise.',
                'price' => 9.99,
                'quantity' => 95,
                'category_name' => 'Farming Tools',
                'images' => [
                    'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600&auto=format&fit=crop',
                ],
                'ratings_avg' => 4.6,
                'ratings_count' => 20,
            ],
            [
                'name' => '3-in-1 Soil Moisture, pH & Light Tester',
                'description' => 'Battery-free dual-probe analog soil testing meter. Quickly read moisture percentages, pH acidity profiles, and sunlight coverage levels by pushing it into the crop soil root systems.',
                'price' => 16.99,
                'quantity' => 60,
                'category_name' => 'Farming Tools',
                'images' => [
                    'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=600&auto=format&fit=crop',
                ],
                'ratings_avg' => 4.4,
                'ratings_count' => 11,
            ],

            // Irrigation
            [
                'name' => 'Complete Automatic Drip Irrigation Kit',
                'description' => 'Covers up to 100 square meters of crop bed. Includes 50m mainline tubing, 30 adjustable drippers, digital automatic hose-timer, pressure reduction valves, and full connections layout.',
                'price' => 79.99,
                'quantity' => 40,
                'category_name' => 'Irrigation Equipment',
                'images' => [
                    '/images/product_irrigation.png',
                ],
                'ratings_avg' => 4.9,
                'ratings_count' => 9,
            ],

            // Tractors & Machinery
            [
                'name' => 'AgroForce 15HP Gasoline Power Weeder',
                'description' => 'High-performance walk-behind petrol rototiller. Ideal for loosening hard clay, removing weeds between tight farm rows, and tilling seedbeds. Adjusts from 30cm to 65cm width.',
                'price' => 849.00,
                'quantity' => 12,
                'category_name' => 'Tractors & Machinery',
                'images' => [
                    '/images/product_weeder.png',
                ],
                'ratings_avg' => 4.7,
                'ratings_count' => 4,
            ],
            [
                'name' => 'Precision Seed Driller Attachment',
                'description' => 'Universal tractor 3-point hitch seed drill. Features 6 adjustable planting rows, depth selector plates, and a seed collection funnel to plant grain, grass, and tiny vegetable seeds.',
                'price' => 420.00,
                'quantity' => 8,
                'category_name' => 'Tractors & Machinery',
                'images' => [
                    '/images/product_driller.png',
                ],
                'ratings_avg' => 4.3,
                'ratings_count' => 3,
            ],

            // Organic Products
            [
                'name' => 'Pure Cold-Pressed Neem Oil Spray (1L)',
                'description' => '100% natural organic pesticide and fungicide. Effectively controls aphids, spider mites, caterpillars, mildew, and black spot disease. Completely safe for beneficial bees.',
                'price' => 19.99,
                'quantity' => 180,
                'category_name' => 'Organic Products',
                'images' => [
                    'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop',
                ],
                'ratings_avg' => 4.6,
                'ratings_count' => 22,
            ],
        ];

        foreach ($productsData as $prod) {
            $cat = $categories[$prod['category_name']];
            Product::updateOrCreate(
                ['name' => $prod['name']],
                [
                    'description' => $prod['description'],
                    'price' => $prod['price'],
                    'quantity' => $prod['quantity'],
                    'category_id' => $cat->id,
                    'seller_id' => $seller->id,
                    'images' => $prod['images'],
                    'ratings_avg' => $prod['ratings_avg'],
                    'ratings_count' => $prod['ratings_count'],
                    'is_available' => true,
                ]
            );
        }

        $this->info('AgroMarket demo database successfully seeded!');
        $this->info('----------------------------------------------');
        $this->info('Admin login:    admin@agromarket.com | password');
        $this->info('Seller login:   seller@agromarket.com | password');
        $this->info('Farmer login:   farmer@agromarket.com | password');
        $this->info('----------------------------------------------');
    }
}
