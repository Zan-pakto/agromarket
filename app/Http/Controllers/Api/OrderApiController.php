<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    protected $orderService;
    protected $orderRepo;

    public function __construct(OrderService $orderService, OrderRepositoryInterface $orderRepo)
    {
        $this->orderService = $orderService;
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $orders = $this->orderRepo->getByUser($request->user_id, $request->input('per_page', 10));

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    public function show(string|int $id)
    {
        try {
            $order = $this->orderRepo->find($id);

            return response()->json([
                'status' => 'success',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cod,stripe,razorpay',
            'coupon_code' => 'nullable|string',
        ]);

        try {
            $billingData = $request->only(['name', 'email', 'phone', 'address']);
            $order = $this->orderService->placeOrder(
                $request->user_id, 
                $billingData, 
                $request->payment_method, 
                $request->coupon_code
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully!',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
