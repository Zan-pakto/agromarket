<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function find(string|int $id)
    {
        return Order::findOrFail($id);
    }
    
    public function create(array $data)
    {
        return Order::create($data);
    }
    
    public function updateStatus(string|int $id, string $status)
    {
        $order = $this->find($id);
        $order->order_status = $status;
        
        // If confirmed or paid
        if ($status === 'confirmed') {
            $order->payment_status = 'paid';
        }
        
        $order->save();
        return $order;
    }
    
    public function getByUser(string|int $userId, int $perPage = 10)
    {
        return Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    public function getBySeller(string|int $sellerId, int $perPage = 10)
    {
        // For standard Eloquent/MongoDB, we can fetch all orders that contain products matching this seller
        // In this implementation, since orders store items as a JSON/array:
        // We will fetch orders, filter them in-memory, or use Eloquent JSON queries.
        // To be highly compatible with SQLite and MongoDB, we fetch order histories:
        $allOrders = Order::all();
        $filteredOrders = $allOrders->filter(function ($order) use ($sellerId) {
            if (!is_array($order->items)) return false;
            foreach ($order->items as $item) {
                $product = Product::find($item['product_id'] ?? null);
                if ($product && (string)$product->seller_id === (string)$sellerId) {
                    return true;
                }
            }
            return false;
        });

        // Paginate the collection manually for maximum portability
        $page = request('page', 1);
        $sliced = $filteredOrders->slice(($page - 1) * $perPage, $perPage)->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $filteredOrders->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
    
    public function getAll(int $perPage = 15)
    {
        return Order::orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    public function getAnalytics()
    {
        $orders = Order::where('order_status', '!=', 'cancelled')->get();
        
        $totalSales = $orders->sum('total_amount');
        $totalOrdersCount = $orders->count();
        $totalUsers = User::count();
        $totalProducts = Product::count();

        // Calculate simple monthly sales for Chart.js
        $monthlySales = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => array_fill(0, 12, 0)
        ];

        foreach ($orders as $order) {
            if ($order->created_at) {
                $month = $order->created_at->month - 1; // 0-indexed
                if ($month >= 0 && $month < 12) {
                    $monthlySales['data'][$month] += $order->total_amount;
                }
            }
        }

        return [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrdersCount,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'monthlySales' => $monthlySales,
            'recentOrders' => Order::orderBy('created_at', 'desc')->limit(5)->get(),
        ];
    }

    public function getSellerAnalytics(string|int $sellerId)
    {
        $allOrders = Order::where('order_status', '!=', 'cancelled')->get();
        
        $sellerRevenue = 0.00;
        $sellerOrdersCount = 0;
        $itemsSold = 0;

        foreach ($allOrders as $order) {
            $isSellerOrder = false;
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    $product = Product::find($item['product_id'] ?? null);
                    if ($product && (string)$product->seller_id === (string)$sellerId) {
                        $sellerRevenue += ($item['price'] * $item['quantity']);
                        $itemsSold += $item['quantity'];
                        $isSellerOrder = true;
                    }
                }
            }
            if ($isSellerOrder) {
                $sellerOrdersCount++;
            }
        }

        $myProducts = Product::where('seller_id', $sellerId)->get();
        $totalProducts = $myProducts->count();
        $outOfStock = $myProducts->where('quantity', '<=', 0)->count();

        return [
            'revenue' => $sellerRevenue,
            'ordersCount' => $sellerOrdersCount,
            'itemsSold' => $itemsSold,
            'totalProducts' => $totalProducts,
            'outOfStock' => $outOfStock,
            'products' => $myProducts,
        ];
    }
}
