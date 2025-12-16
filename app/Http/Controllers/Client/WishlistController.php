<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.firstImage', 'product.category'])
            ->whereHas('product', function ($query) {
                $query->withCount(['reviews as reviews_count' => function ($q) {
                    $q->where('status', 'approved');
                }])
                    ->withAvg(['reviews as reviews_avg' => function ($q) {
                        $q->where('status', 'approved');
                    }], 'rating');
            })
            ->paginate(8);

        // Load reviews count và avg cho từng product
        foreach ($wishlists as $wishlist) {
            $wishlist->product->loadCount(['reviews as reviews_count' => function ($q) {
                $q->where('status', 'approved');
            }]);
            $wishlist->product->loadAvg(['reviews as reviews_avg' => function ($q) {
                $q->where('status', 'approved');
            }], 'rating');
        }

        return view('client.pages.wishlist', compact('wishlists'));
    }

    /**
     * Toggle wishlist - Thêm nếu chưa có, xóa nếu đã có (AJAX)
     */
    public function toggle($product_id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if ($wishlist) {
            // Đã có trong wishlist → Xóa
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Đã xóa khỏi danh sách yêu thích!'
            ]);
        } else {
            // Chưa có → Thêm mới
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product_id
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Đã thêm vào danh sách yêu thích!'
            ]);
        }
    }

    /**
     * Thêm tất cả sản phẩm trong wishlist vào giỏ hàng
     */
    public function addAllToCart()
    {
        try {
            $wishlists = Wishlist::where('user_id', Auth::id())
                ->with('product')
                ->get();

            if ($wishlists->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh sách yêu thích trống!'
                ]);
            }

            $addedCount = 0;
            foreach ($wishlists as $wishlist) {
                if ($wishlist->product && $wishlist->product->status == 'in_stock') {
                    // Thêm vào giỏ hàng (sử dụng logic từ CartItemController)
                    $cartItem = \App\Models\CartItem::where('user_id', Auth::id())
                        ->where('product_id', $wishlist->product_id)
                        ->first();

                    if ($cartItem) {
                        // Nếu đã có trong giỏ, tăng số lượng
                        $cartItem->quantity += 1;
                        $cartItem->save();
                    } else {
                        // Thêm mới
                        \App\Models\CartItem::create([
                            'user_id' => Auth::id(),
                            'product_id' => $wishlist->product_id,
                            'quantity' => 1
                        ]);
                    }
                    $addedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Đã thêm {$addedCount} sản phẩm vào giỏ hàng!",
                'count' => $addedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa tất cả sản phẩm khỏi wishlist
     */
    public function clearAll()
    {
        try {
            $count = Wishlist::where('user_id', Auth::id())->count();

            if ($count == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh sách yêu thích đã trống!'
                ]);
            }

            Wishlist::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => "Đã xóa {$count} sản phẩm khỏi danh sách yêu thích!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
