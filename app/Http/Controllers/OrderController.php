<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $cartService;
    protected $orderService;
    protected $orderRepo;

    public function __construct(CartService $cartService, OrderService $orderService, OrderRepositoryInterface $orderRepo)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->orderRepo = $orderRepo;
    }

    public function checkout()
    {
        $userId = Auth::id();
        $couponCode = session()->get('coupon_code');
        $cart = $this->cartService->getDetails($userId, $couponCode);

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add products before checkout.');
        }

        return view('orders.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cod,stripe,razorpay',
        ]);

        $userId = Auth::id();
        $couponCode = session()->get('coupon_code');

        try {
            $billingData = $request->only(['name', 'email', 'phone', 'address']);
            $order = $this->orderService->placeOrder($userId, $billingData, $request->payment_method, $couponCode);

            // Forget coupon code upon successful checkout
            session()->forget('coupon_code');

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully! Thank you for buying from AgroMarket.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(string|int $id)
    {
        $order = $this->orderRepo->find($id);

        // Ensure user is authorized to view this order (either buyer, seller of items, or admin)
        $user = Auth::user();
        if (!$user->isAdmin() && (string)$order->user_id !== (string)$user->id) {
            // Check if seller owns any items
            $isSellerOfItem = false;
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    if ((string)($item['seller_id'] ?? null) === (string)$user->id) {
                        $isSellerOfItem = true;
                        break;
                    }
                }
            }
            if (!$isSellerOfItem) {
                abort(403, 'Unauthorized to view this order.');
            }
        }

        return view('orders.show', compact('order'));
    }

    public function track(string|int $id)
    {
        $order = $this->orderRepo->find($id);

        // Ensure only buyer or admin can track
        $user = Auth::user();
        if (!$user->isAdmin() && (string)$order->user_id !== (string)$user->id) {
            abort(403, 'Unauthorized.');
        }

        return view('orders.track', compact('order'));
    }
}
