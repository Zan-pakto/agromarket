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

    public function becomeSeller(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone' => 'required|string|max:20',
            'store_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);

        $user->name = $request->store_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'seller';
        $user->status = 'pending';
        $user->save();

        Notification::create([
            'user_id' => $user->id,
            'title' => "Seller Application Submitted!",
            'message' => "Your store application has been successfully submitted and is pending administrator review.",
            'type' => 'seller_status',
        ]);

        return redirect()->route('dashboard')->with('success', 'Your seller application has been submitted successfully and is now pending admin approval.');
    }

    public function revertFarmer(Request $request)
    {
        $user = Auth::user();

        if ($user->isSeller() && !$user->isApproved()) {
            $user->role = 'farmer';
            $user->status = 'approved';
            $user->save();

            Notification::create([
                'user_id' => $user->id,
                'title' => "Switched back to Farmer Account",
                'message' => "Your role has been updated back to Farmer. Your pending or rejected seller application has been removed.",
                'type' => 'seller_status',
            ]);

            return redirect()->route('dashboard')->with('success', 'Your account has been switched back to a standard Farmer account.');
        }

        return redirect()->route('dashboard')->with('error', 'Only unapproved seller accounts can revert to farmer status.');
    }
}
