<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        // Tìm kiếm theo tên sản phẩm hoặc user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Lọc theo rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Lọc theo status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reviews = $query->latest()->get();

        // Thống kê
        $stats = [
            'total' => Review::count(),
            'pending' => Review::pending()->count(),
            'approved' => Review::approved()->count(),
            'rejected' => Review::rejected()->count(),
            'average_rating' => round(Review::avg('rating'), 1),
        ];

        // Phân bố rating
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('rating', $i)->count();
            $percentage = $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0;
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return view('admin.pages.reviews.index', compact('reviews', 'stats', 'ratingDistribution'));
    }

    public function approve($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->approve();

            return redirect()->back()->with('success', 'Đã duyệt đánh giá thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->reject();

            return redirect()->back()->with('success', 'Đã từ chối đánh giá!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $review = Review::findOrFail($request->review_id);
            $review->delete();

            return redirect()->back()->with('success', 'Đã xóa đánh giá thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }
}
