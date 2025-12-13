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
        $query = Product::with('firstImage')->where('status', 'in_stock');
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }
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
        $products = $query->paginate(12);

        // Get wishlist product IDs for current user
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Auth::user()->wishlists()->pluck('product_id')->toArray();
        }

        return view('client.pages.products', compact('categories', 'products', 'wishlistProductIds'));
    }
    public function showDetail($slug)
    {
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Auth::user()->wishlists()->pluck('product_id')->toArray();
        }
        $product = Product::with(['category','images'])->where('slug',$slug)->firstOrFail();
        $relatedProducts=Product::where("category_id",$product->category_id)
        ->where("id",'!=',$product->id)
        ->limit(6)
        ->get();
        return view('client.pages.product-detail', compact('product','wishlistProductIds','relatedProducts'));
    }
}
