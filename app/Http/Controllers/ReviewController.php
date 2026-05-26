<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        // Verify user has purchased this product
        $hasPurchased = false;
        
        $orders = Order::where('user_id', $userId)->get();
        foreach ($orders as $order) {
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    if (isset($item['product_id']) && (string)$item['product_id'] === (string)$product->id) {
                        $hasPurchased = true;
                        break 2;
                    }
                }
            }
        }

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        try {
            $this->productService->addReview($product->id, $userId, (int)$request->rating, $request->comment);
            return redirect()->back()->with('success', 'Thank you for your review!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
