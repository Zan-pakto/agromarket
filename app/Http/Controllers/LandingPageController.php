<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $categories = Category::limit(6)->get();
        $featuredProducts = $this->productRepo->getFeatured(8);
        
        // Static Testimonials
        $testimonials = [
            [
                'name' => 'Rajesh Kumar',
                'role' => 'Organic Farmer, Punjab',
                'feedback' => 'AgroMarket has transformed how I source seeds. The high-germination organic tomato seeds resulted in a 30% yield boost this season!',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop',
                'rating' => 5
            ],
            [
                'name' => 'Savitri Devi',
                'role' => 'Horticulturist, Karnataka',
                'feedback' => 'I ordered a complete drip irrigation kit. The price was 20% lower than my local retail store, and delivery was exceptionally fast.',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop',
                'rating' => 5
            ],
            [
                'name' => 'Vikram Singh',
                'role' => 'Wheat Grower, Haryana',
                'feedback' => 'Buying high-grade fertilizers and tools is now just a tap away. The cash-on-delivery option gives me ultimate peace of mind.',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop',
                'rating' => 4
            ]
        ];

        return view('welcome', compact('categories', 'featuredProducts', 'testimonials'));
    }

    public function shop(Request $request)
    {
        $categories = Category::all();
        $filters = $request->only(['search', 'category', 'min_price', 'max_price', 'rating', 'sort']);
        $products = $this->productRepo->all($filters, 12);

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(string|int $id)
    {
        $product = $this->productRepo->find($id);
        $relatedProducts = $this->productRepo->getRelated($id, 4);

        // Store in recently viewed products using Laravel Session
        $recentlyViewed = session()->get('recently_viewed', []);
        if (!in_array((string)$product->id, $recentlyViewed)) {
            array_unshift($recentlyViewed, (string)$product->id);
            // Limit to 4 items
            $recentlyViewed = array_slice($recentlyViewed, 0, 4);
            session()->put('recently_viewed', $recentlyViewed);
        }

        $recentProducts = Product::whereIn('id', $recentlyViewed)
            ->where('id', '!=', $product->id)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts', 'recentProducts'));
    }

    public function toggleWishlist(string|int $productId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please log in to add to your wishlist.'], 401);
        }

        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Removed from Wishlist!';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $status = 'added';
            $message = 'Added to Wishlist!';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function faq()
    {
        return view('pages.faq');
    }
}
