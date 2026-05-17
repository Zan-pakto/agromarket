<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    protected $productRepo;
    protected $productService;

    public function __construct(ProductRepositoryInterface $productRepo, ProductService $productService)
    {
        $this->productRepo = $productRepo;
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'min_price', 'max_price', 'rating', 'sort']);
        $products = $this->productRepo->all($filters, $request->input('per_page', 12));

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    public function show(string|int $id)
    {
        try {
            $product = $this->productRepo->find($id);
            $related = $this->productRepo->getRelated($id, 4);

            return response()->json([
                'status' => 'success',
                'product' => $product,
                'related' => $related
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.'
            ], 404);
        }
    }

    public function storeReview(Request $request, string|int $productId)
    {
        $request->validate([
            'user_id' => 'required', // for simple stateless API, we accept user_id in payload or use Auth
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        try {
            $review = $this->productService->addReview($productId, $request->user_id, (int)$request->rating, $request->comment);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Review submitted successfully!',
                'review' => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
