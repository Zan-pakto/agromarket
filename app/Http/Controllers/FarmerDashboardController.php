<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FarmerDashboardController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Recent Orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Wishlist
        $wishlistItems = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->pluck('product')
            ->filter(); // filter out nulls in case product deleted

        // Recommended Products: Products in similar categories as wishlisted, or highest rated
        $wishCategoryIds = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->pluck('product.category_id')
            ->unique()
            ->filter();

        if ($wishCategoryIds->count() > 0) {
            $recommended = Product::where('is_available', true)
                ->whereIn('category_id', $wishCategoryIds)
                ->orderBy('ratings_avg', 'desc')
                ->limit(4)
                ->get();
        } else {
            $recommended = Product::where('is_available', true)
                ->orderBy('ratings_avg', 'desc')
                ->limit(4)
                ->get();
        }

        // In-app Notifications
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboards.farmer', compact('user', 'recentOrders', 'wishlistItems', 'recommended', 'notifications'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $this->productService->addReview($request->product_id, $userId, (int)$request->rating, $request->comment);

        return redirect()->back()->with('success', 'Thank you for your rating and review!');
    }

    public function markNotificationsRead()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}
