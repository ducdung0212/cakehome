<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'orderItems', 'shippingAddress']);

        // Tìm kiếm theo mã đơn hoặc tên khách hàng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        // Lọc theo trạng thái đơn hàng
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(10);

        // Thống kê theo trạng thái
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.pages.orders.index', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'orderItems.product.images',
            'orderItems.product.category',
            'shippingAddress',
            'payment',
            'orderStatusHistories' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.pages.orders.detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,ready,shipping,delivered,completed,cancelled',
                'notes' => 'nullable|string'
            ]);

            $order = Order::with('payment')->findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // === VALIDATION RULES ===

            // 1. Không thể thay đổi đơn hàng đã hoàn thành
            if ($oldStatus === 'completed') {
                return redirect()->back()
                    ->with('error', 'Không thể thay đổi trạng thái đơn hàng đã hoàn thành!');
            }

            // 2. Không thể thay đổi đơn hàng đã hủy
            if ($oldStatus === 'cancelled') {
                return redirect()->back()
                    ->with('error', 'Không thể thay đổi trạng thái đơn hàng đã hủy!');
            }

            // 3. Đơn đã thanh toán MoMo không thể hủy trực tiếp (cần hoàn tiền)
            if (
                $newStatus === 'cancelled' &&
                $order->payment &&
                $order->payment->payment_method === 'momo' &&
                $order->payment->status === 'completed'
            ) {
                return redirect()->back()
                    ->with('error', 'Đơn hàng đã thanh toán qua MoMo không thể hủy trực tiếp. Vui lòng liên hệ bộ phận tài chính để xử lý hoàn tiền!');
            }

            // 4. Validate luồng trạng thái hợp lệ 
            $isPickup = $order->delivery_method === 'pickup';

            $validTransitions = [
                'pending' => ['confirmed', 'cancelled'],
                'confirmed' => ['processing', 'cancelled'],
                'processing' => ['ready', 'cancelled'], // Chuyển sang đã chuẩn bị
                'ready' => $isPickup ? ['completed', 'cancelled'] : ['shipping', 'cancelled'], // Pickup: ready -> completed, Delivery: ready -> shipping
                'shipping' => ['delivered', 'cancelled'],
                'delivered' => ['completed', 'cancelled'],
            ];

            // Kiểm tra chuyển đổi có hợp lệ không
            if (isset($validTransitions[$oldStatus])) {
                if (!in_array($newStatus, $validTransitions[$oldStatus])) {
                    $allowedStatuses = implode(', ', $validTransitions[$oldStatus]);
                    return redirect()->back()
                        ->with('error', "Không thể chuyển từ trạng thái '{$oldStatus}' sang '{$newStatus}'. Chỉ có thể chuyển sang: {$allowedStatuses}");
                }
            }

            // 5. Cảnh báo khi hủy đơn đã giao hàng
            if ($newStatus === 'cancelled' && in_array($oldStatus, ['ready', 'shipping', 'delivered'])) {
                if (!$request->has('confirm_cancel')) {
                    return redirect()->back()
                        ->with('warning', 'Đơn hàng đã/đang giao. Bạn có chắc muốn hủy? Vui lòng thêm ghi chú lý do.')
                        ->withInput();
                }
            }

            // === CẬP NHẬT TRẠNG THÁI ===
            DB::transaction(function () use ($request, $order, $newStatus) {
                // QUAN TRỌNG: Lưu notes vào thuộc tính tạm của model để Observer có thể sử dụng
                if ($request->notes) {
                    $order->status_notes = $request->notes;
                }

                // Cập nhật trạng thái đơn hàng (Observer sẽ tự động kích hoạt sau dòng này)
                $order->update([
                    'status' => $newStatus
                ]);

                // Tự động cập nhật payment status khi hủy đơn
                if ($newStatus === 'cancelled' && $order->payment && $order->payment->status === 'pending') {
                    $order->payment->update(['status' => 'failed']);
                }

                // Khi đơn hàng hoàn thành => coi như đã thanh toán
                if ($newStatus === 'completed' && $order->payment && $order->payment->status !== 'completed') {
                    $paymentUpdate = ['status' => 'completed'];
                    if (!$order->payment->paid_at) {
                        $paymentUpdate['paid_at'] = now();
                    }
                    $order->payment->update($paymentUpdate);
                }
            });

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage());
        }
    }
}
