<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $userId = Auth::id();
        $couponCode = session()->get('coupon_code');
        $cart = $this->cartService->getDetails($userId, $couponCode);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $this->cartService->addItem($request->product_id, (int)$request->quantity, $userId);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Product added to cart successfully!', 'cartCount' => count($this->cartService->getCart($userId))]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $this->cartService->updateQuantity($request->product_id, (int)$request->quantity, $userId);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $userId = Auth::id();
        $this->cartService->removeItem($request->product_id, $userId);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $userId = Auth::id();
        $cartDetails = $this->cartService->getDetails($userId, $request->code);

        if ($cartDetails['discount'] > 0) {
            session()->put('coupon_code', $request->code);
            return redirect()->route('cart.index')->with('success', 'Coupon applied successfully! Saved $' . $cartDetails['discount']);
        }

        return redirect()->route('cart.index')->with('error', 'Invalid, expired, or inapplicable coupon code.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return redirect()->route('cart.index')->with('success', 'Coupon code removed.');
    }
}
