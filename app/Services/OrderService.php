<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Notification;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Str;

class OrderService
{
    protected $orderRepo;
    protected $cartService;

    public function __construct(OrderRepositoryInterface $orderRepo, CartService $cartService)
    {
        $this->orderRepo = $orderRepo;
        $this->cartService = $cartService;
    }

    public function placeOrder(int|string $userId, array $billingData, string $paymentMethod, ?string $couponCode = null): Order
    {
        // 1. Get Cart Details
        $cartDetails = $this->cartService->getDetails($userId, $couponCode);
        
        if (empty($cartDetails['items'])) {
            throw new \Exception('Your cart is empty.');
        }

        // 2. Validate stock and reduce inventory
        $orderItems = [];
        foreach ($cartDetails['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->quantity < $item['quantity']) {
                throw new \Exception("Product '{$product->name}' is out of stock or does not have enough inventory.");
            }

            // Decrement Stock
            $product->quantity -= $item['quantity'];
            if ($product->quantity === 0) {
                $product->is_available = false;
            }
            $product->save();

            $orderItems[] = [
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'seller_id' => $product->seller_id,
            ];
        }

        // 3. Create the Order
        $orderData = [
            'user_id' => $userId,
            'items' => $orderItems,
            'total_amount' => $cartDetails['total'],
            'discount_amount' => $cartDetails['discount'],
            'coupon_code' => $couponCode,
            'billing_name' => $billingData['name'],
            'billing_email' => $billingData['email'],
            'billing_phone' => $billingData['phone'],
            'billing_address' => $billingData['address'],
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'paid', // cod defaults to pending, card defaults to paid
            'order_status' => 'pending',
            'tracking_number' => 'AM-' . strtoupper(Str::random(10)),
        ];

        $order = $this->orderRepo->create($orderData);

        // 4. Send Notifications
        // A. Farmer Notification
        Notification::create([
            'user_id' => $userId,
            'title' => 'Order Placed successfully!',
            'message' => "Your order {$order->tracking_number} has been received and is being processed. Total amount: {$order->total_amount}.",
            'type' => 'order',
        ]);

        // B. Sellers Notification (Group by seller)
        $sellerIds = [];
        foreach ($orderItems as $item) {
            $sellerIds[] = $item['seller_id'];
        }
        $sellerIds = array_unique($sellerIds);

        foreach ($sellerIds as $sellerId) {
            Notification::create([
                'user_id' => $sellerId,
                'title' => 'New Order Received!',
                'message' => "You have received a new order {$order->tracking_number} containing items from your catalog.",
                'type' => 'order',
            ]);
        }

        // 5. Clear Cart
        $this->cartService->clearCart($userId);

        return $order;
    }

    public function updateOrderStatus(int|string $orderId, string $status, ?string $trackingNumber = null): Order
    {
        $order = $this->orderRepo->find($orderId);
        $order->order_status = $status;
        
        if ($trackingNumber) {
            $order->tracking_number = $trackingNumber;
        }

        if ($status === 'delivered') {
            $order->payment_status = 'paid';
        }

        $order->save();

        // Notify Buyer
        Notification::create([
            'user_id' => $order->user_id,
            'title' => "Order Status Updated: {$status}",
            'message' => "Your order {$order->tracking_number} status has been updated to '{$status}'.",
            'type' => 'order',
        ]);

        return $order;
    }
}
