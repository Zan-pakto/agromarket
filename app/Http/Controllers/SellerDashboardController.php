<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    protected $productService;
    protected $orderService;
    protected $orderRepo;

    public function __construct(ProductService $productService, OrderService $orderService, OrderRepository $orderRepo)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->orderRepo = $orderRepo;
    }

    public function index()
    {
        $sellerId = Auth::id();
        $analytics = $this->orderRepo->getSellerAnalytics($sellerId);
        
        // Fetch paginated products for the seller
        $products = Product::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'products_page');
            
        // Fetch paginated orders for the seller
        $orders = $this->orderRepo->getBySeller($sellerId, 5);
        $categories = Category::all();

        return view('dashboards.seller', compact('analytics', 'products', 'orders', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $sellerId = Auth::id();
        $images = $request->file('images');

        $this->productService->createProduct($request->only(['name', 'description', 'price', 'quantity', 'category_id']), $images, $sellerId);

        return redirect()->route('dashboard')->with('success', 'Product added successfully and is now active in the marketplace.');
    }

    public function updateProduct(Request $request, string|int $id)
    {
        $product = Product::findOrFail($id);
        
        // Ensure seller owns the product
        if ((string)$product->seller_id !== (string)Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $newImages = $request->file('images');

        $this->productService->updateProduct($id, $request->only(['name', 'description', 'price', 'quantity', 'category_id']), $newImages);

        return redirect()->route('dashboard')->with('success', 'Product inventory updated successfully.');
    }

    public function deleteProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        
        if ((string)$product->seller_id !== (string)Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Product removed from marketplace.');
    }

    public function updateOrderStatus(Request $request, string|int $id)
    {
        $request->validate([
            'order_status' => 'required|string|in:confirmed,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $this->orderService->updateOrderStatus($id, $request->order_status, $request->tracking_number);

        return redirect()->route('dashboard')->with('success', 'Order status updated successfully.');
    }
}
