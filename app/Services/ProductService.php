<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\Notification;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductService
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function createProduct(array $data, array $imageFiles, int|string $sellerId): Product
    {
        $imagePaths = [];
        foreach ($imageFiles as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('products', 'public');
                $imagePaths[] = '/storage/' . $path;
            }
        }

        if (empty($imagePaths)) {
            $imagePaths[] = asset('images/product_placeholder.png');
        }

        $data['images'] = $imagePaths;
        $data['seller_id'] = $sellerId;
        $data['ratings_avg'] = 0.00;
        $data['ratings_count'] = 0;
        $data['is_available'] = (int)$data['quantity'] > 0;

        return $this->productRepo->create($data);
    }

    public function updateProduct(int|string $id, array $data, ?array $newImageFiles = null): Product
    {
        $product = $this->productRepo->find($id);

        if ($newImageFiles && count($newImageFiles) > 0) {
            $imagePaths = [];
            foreach ($newImageFiles as $file) {
                if ($file instanceof UploadedFile) {
                    $path = $file->store('products', 'public');
                    $imagePaths[] = '/storage/' . $path;
                }
            }
            if (count($imagePaths) > 0) {
                // Delete old images from storage (excluding external placeholders)
                if (is_array($product->images)) {
                    foreach ($product->images as $oldImage) {
                        if (str_starts_with($oldImage, '/storage/')) {
                            $cleanPath = str_replace('/storage/', '', $oldImage);
                            Storage::disk('public')->delete($cleanPath);
                        }
                    }
                }
                $data['images'] = $imagePaths;
            }
        }

        $data['is_available'] = (int)($data['quantity'] ?? $product->quantity) > 0;

        return $this->productRepo->update($id, $data);
    }

    public function addReview(int|string $productId, int|string $userId, int $rating, ?string $comment = null): Review
    {
        $product = Product::findOrFail($productId);

        // Check if user already reviewed this product to avoid duplicates
        $review = Review::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['rating' => $rating, 'comment' => $comment]
        );

        // Recalculate average ratings
        $reviews = Review::where('product_id', $productId)->get();
        $avg = $reviews->avg('rating');
        $count = $reviews->count();

        $product->ratings_avg = round($avg, 2);
        $product->ratings_count = $count;
        $product->save();

        // Notify Seller about review
        Notification::create([
            'user_id' => $product->seller_id,
            'title' => 'New Product Review Received!',
            'message' => "A farmer rated your product '{$product->name}' with {$rating} stars.",
            'type' => 'general',
        ]);

        return $review;
    }
}
