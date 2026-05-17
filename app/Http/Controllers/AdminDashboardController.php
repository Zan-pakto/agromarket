<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Notification;
use App\Repositories\Eloquent\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index()
    {
        $analytics = $this->orderRepo->getAnalytics();
        $categories = Category::all();
        $sellersPending = User::where('role', 'seller')->where('status', 'pending')->get();

        return view('dashboards.admin', compact('analytics', 'categories', 'sellersPending'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboards.admin-users', compact('users'));
    }

    public function toggleSellerStatus(Request $request, string|int $id)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected',
        ]);

        $user = User::findOrFail($id);
        
        if ($user->role !== 'seller') {
            return redirect()->back()->with('error', 'Only sellers can have their status changed.');
        }

        $user->status = $request->status;
        $user->save();

        // Create alert notification for the seller
        Notification::create([
            'user_id' => $user->id,
            'title' => "Store Application " . ucfirst($request->status) . "!",
            'message' => $request->status === 'approved' 
                ? "Congratulations! Your seller application has been approved. You can now publish products to the marketplace."
                : "We regret to inform you that your seller application has been rejected. Please review our seller policies and apply again.",
            'type' => 'seller_status',
        ]);

        return redirect()->back()->with('success', 'Seller account status successfully updated to: ' . $request->status);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100', // e.g. Tailwind class
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? 'fas fa-seedling',
        ]);

        return redirect()->back()->with('success', 'New product category created successfully.');
    }

    public function deleteCategory(string|int $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category and its related products deleted.');
    }

    public function deleteProduct(string|int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Product permanently removed by administrator.');
    }

    public function updateOrderStatus(Request $request, string|int $id)
    {
        $request->validate([
            'order_status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->order_status = $request->order_status;
        
        if ($request->order_status === 'delivered') {
            $order->payment_status = 'paid';
        }

        $order->save();

        Notification::create([
            'user_id' => $order->user_id,
            'title' => "Order Status Changed: " . ucfirst($request->order_status),
            'message' => "An administrator has updated your order {$order->tracking_number} status to '{$request->order_status}'.",
            'type' => 'order',
        ]);

        return redirect()->back()->with('success', 'Order status updated.');
    }
}
