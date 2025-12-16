<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá sản phẩm!'], 401);
                }
                return redirect()->back()->with('error', 'Bạn cần đăng nhập để đánh giá sản phẩm!');
            }

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|min:10|max:500'
            ], [
                'product_id.required' => 'Sản phẩm không hợp lệ.',
                'product_id.exists' => 'Sản phẩm không tồn tại.',
                'rating.required' => 'Vui lòng chọn số sao đánh giá.',
                'rating.min' => 'Đánh giá phải từ 1 đến 5 sao.',
                'rating.max' => 'Đánh giá phải từ 1 đến 5 sao.',
                'comment.required' => 'Vui lòng nhập nhận xét về sản phẩm.',
                'comment.min' => 'Nhận xét phải có ít nhất 10 ký tự.',
                'comment.max' => 'Nội dung đánh giá không được quá 500 ký tự.'
            ]);

            // Kiểm tra user đã mua sản phẩm chưa
            $hasPurchased = OrderItem::whereHas('order', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('status', 'completed');
            })->where('product_id', $request->product_id)->exists();

            if (!$hasPurchased) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Bạn chỉ có thể đánh giá sản phẩm đã mua!'], 403);
                }
                return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua!');
            }

            // Kiểm tra đã đánh giá chưa
            $existingReview = Review::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingReview) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi!'], 409);
                }
                return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
            }

            // Tạo review
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending' // Chờ admin duyệt
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn sẽ được hiển thị sau khi admin duyệt.'
                ]);
            }

            return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn sẽ được hiển thị sau khi admin duyệt.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Throwable $th) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $th->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    public function canReview($productId)
    {
        if (!Auth::check()) {
            return response()->json(['canReview' => false, 'message' => 'Bạn cần đăng nhập']);
        }

        // Kiểm tra đã mua sản phẩm
        $hasPurchased = OrderItem::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('status', 'completed');
        })->where('product_id', $productId)->exists();

        if (!$hasPurchased) {
            return response()->json(['canReview' => false, 'message' => 'Bạn chưa mua sản phẩm này']);
        }

        // Kiểm tra đã đánh giá chưa
        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if ($hasReviewed) {
            return response()->json(['canReview' => false, 'message' => 'Bạn đã đánh giá sản phẩm này']);
        }

        return response()->json(['canReview' => true]);
    }
}
