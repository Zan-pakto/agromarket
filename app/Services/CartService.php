<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Coupon;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function getCart($userId = null): array
    {
        if ($userId) {
            $dbCart = $this->cartRepo->getByUserId($userId);
            return $dbCart->items ?? [];
        }

        return Session::get('cart', []);
    }

    public function addItem(int|string $productId, int $quantity, $userId = null): array
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart($userId);

        $exists = false;
        foreach ($cart as &$item) {
            if ((string)$item['product_id'] === (string)$productId) {
                $item['quantity'] = min($item['quantity'] + $quantity, $product->quantity);
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $cart[] = [
                'product_id' => $productId,
                'quantity' => min($quantity, $product->quantity),
                'price' => (float)$product->price,
            ];
        }

        $this->saveCart($cart, $userId);
        return $cart;
    }

    public function removeItem(int|string $productId, $userId = null): array
    {
        $cart = $this->getCart($userId);
        
        $cart = array_filter($cart, function ($item) use ($productId) {
            return (string)$item['product_id'] !== (string)$productId;
        });

        // Reset array keys
        $cart = array_values($cart);

        $this->saveCart($cart, $userId);
        return $cart;
    }

    public function updateQuantity(int|string $productId, int $quantity, $userId = null): array
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart($userId);

        foreach ($cart as &$item) {
            if ((string)$item['product_id'] === (string)$productId) {
                $item['quantity'] = max(1, min($quantity, $product->quantity));
                break;
            }
        }

        $this->saveCart($cart, $userId);
        return $cart;
    }

    public function clearCart($userId = null): void
    {
        if ($userId) {
            $this->cartRepo->clearCart($userId);
        } else {
            Session::forget('cart');
        }
    }

    public function getDetails($userId = null, ?string $couponCode = null): array
    {
        $items = $this->getCart($userId);
        $cartDetails = [];
        $subtotal = 0.00;

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->is_available) {
                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;

                $cartDetails[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => (float)$product->price,
                    'quantity' => $item['quantity'],
                    'stock' => $product->quantity,
                    'image' => $product->getFirstImage(),
                    'total' => $itemTotal,
                ];
            }
        }

        $discount = 0.00;
        $appliedCoupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $appliedCoupon = $coupon;
            }
        }

        $tax = round(($subtotal - $discount) * 0.05, 2); // 5% agricultural tax
        $total = max(0.00, $subtotal - $discount + $tax);

        return [
            'items' => $cartDetails,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon' => $appliedCoupon,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    protected function saveCart(array $cart, $userId = null): void
    {
        if ($userId) {
            $this->cartRepo->updateCart($userId, $cart);
        } else {
            Session::put('cart', $cart);
        }
    }

    public function mergeSessionCartToUser(int|string $userId): void
    {
        $sessionCart = Session::get('cart', []);
        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $sessionItem) {
            $this->addItem($sessionItem['product_id'], $sessionItem['quantity'], $userId);
        }

        Session::forget('cart');
    }
}
