<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách khách hàng
     */
    public function index()
    {
        $users = User::with('role')
            ->where('role_id', '!=', 1) // Không hiển thị admin
            ->orderBy('created_at', 'desc')
            ->get();

        // Chuẩn hóa dữ liệu
        foreach ($users as $user) {
            if (!$user->name) {
                $user->name = "Chưa cập nhật";
            }
            // Trạng thái tiếng Việt
            $user->status_text = $this->getStatusText($user->status);
        }

        $title = 'Quản lý khách hàng';
        return view('admin.pages.users.index', compact('title', 'users'));
    }

    /**
     * Hiển thị chi tiết khách hàng
     */
    public function show($id)
    {
        $user = User::with([
            'orders' => function ($query) {
                $query->latest();
            },
            'shippingAddresses'
        ])->findOrFail($id);

        // Thêm status_text
        $user->status_text = $this->getStatusText($user->status);

        // Thêm status_text cho orders
        foreach ($user->orders as $order) {
            $order->status_text = $this->getOrderStatusText($order->status);
        }

        $title = 'Chi tiết khách hàng: ' . $user->name;

        return view('admin.pages.users.detail', compact('user', 'title'));
    }

    /**
     * Kích hoạt tài khoản
     */
    public function activate(Request $request)
    {
        $userId = $request->userId;
        $user = User::findOrFail($userId);

        $user->status = 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kích hoạt tài khoản thành công!',
            'status' => 'Đã kích hoạt'
        ]);
    }

    /**
     * Cập nhật trạng thái người dùng (chặn/bỏ chặn/xóa)
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = $request->status;

        $user->status = $status;
        $user->save();

        $statusMessages = [
            'active' => 'Đã kích hoạt',
            'banned' => 'Đã chặn',
            'deleted' => 'Đã xóa',
            'pending' => 'Chờ kích hoạt'
        ];

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!',
            'status' => $statusMessages[$status] ?? $status
        ]);
    }

    /**
     * Xóa người dùng (soft delete)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'deleted';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa người dùng thành công!'
        ]);
    }

    /**
     * Lấy text trạng thái tiếng Việt
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ kích hoạt',
            'active' => 'Đã kích hoạt',
            'banned' => 'Đã chặn',
            'deleted' => 'Đã xóa'
        ];

        return $statusTexts[$status] ?? 'Không xác định';
    }

    /**
     * Lấy text trạng thái đơn hàng
     */
    private function getOrderStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền'
        ];

        return $statusTexts[$status] ?? $status;
    }
}
