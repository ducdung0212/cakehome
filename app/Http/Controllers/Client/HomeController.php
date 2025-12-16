<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $bestSellingProducts = Product::select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(8)
            ->with('firstImage')
            ->withCount(['reviews as reviews_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withAvg(['reviews as reviews_avg' => function ($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->get();

        // Get wishlist product IDs for current user
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Auth::user()->wishlists()->pluck('product_id')->toArray();
        }

        return view('client.pages.home', compact('categories', 'bestSellingProducts', 'wishlistProductIds'));
    }
}
