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
            ->paginate(8);
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
}
