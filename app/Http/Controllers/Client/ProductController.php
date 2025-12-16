<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $searchQuery = $request->input('q');
        $query = Product::with('firstImage')->where('status', 'in_stock');

        // Search filter
        if ($request->filled('q')) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                    ->orWhereHas('category', function ($q) use ($searchQuery) {
                        $q->where('name', 'LIKE', "%{$searchQuery}%");
                    });
            });
        }

        // Category filter
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price filter
        if ($request->has('price_range')) {
            switch ($request->price_range) {
                case 'price1':
                    $query->where('price', '<', 100000);
                    break;
                case 'price2':
                    $query->whereBetween('price', [100000, 300000]);
                    break;
                case 'price3':
                    $query->where('price', '>', 300000);
                    break;
            }
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query
            ->withCount(['reviews as reviews_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withAvg(['reviews as reviews_avg' => function ($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->paginate(12)->appends($request->except('page'));

        // Get wishlist product IDs for current user
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Auth::user()->wishlists()->pluck('product_id')->toArray();
        }

        return view('client.pages.products', compact('categories', 'products', 'wishlistProductIds', 'searchQuery'));
    }
    public function showDetail($slug)
    {
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Auth::user()->wishlists()->pluck('product_id')->toArray();
        }

        $product = Product::with(['category', 'images'])->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::where("category_id", $product->category_id)
            ->where("id", '!=', $product->id)
            ->withCount(['reviews as reviews_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withAvg(['reviews as reviews_avg' => function ($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->limit(6)
            ->get();

        // Load approved reviews with user information
        $reviews = $product->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->paginate(5);

        // Calculate review statistics
        $reviewStats = [
            'total' => $product->reviews()->approved()->count(),
            'average' => round($product->reviews()->approved()->avg('rating') ?? 0, 1),
            'distribution' => []
        ];

        // Get rating distribution
        for ($i = 5; $i >= 1; $i--) {
            $count = $product->reviews()->approved()->where('rating', $i)->count();
            $percentage = $reviewStats['total'] > 0 ? round(($count / $reviewStats['total']) * 100, 1) : 0;
            $reviewStats['distribution'][$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        // Check if current user can review this product
        $canReview = false;
        $userPendingReview = null;
        if (Auth::check()) {
            $hasPurchased = Auth::user()->orders()
                ->where('status', 'completed')
                ->whereHas('orderItems', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();

            $hasReviewed = Auth::user()->reviews()
                ->where('product_id', $product->id)
                ->exists();

            // Load pending review của user hiện tại
            $userPendingReview = Auth::user()->reviews()
                ->where('product_id', $product->id)
                ->where('status', 'pending')
                ->first();

            $canReview = $hasPurchased && !$hasReviewed;
        }

        return view('client.pages.product-detail', compact('product', 'wishlistProductIds', 'relatedProducts', 'reviews', 'reviewStats', 'canReview', 'userPendingReview'));
    }
}
