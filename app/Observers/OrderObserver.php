<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Notification;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Tạo history ban đầu khi đơn hàng được tạo
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'note' => 'Đơn hàng được tạo' // Đã sửa thành 'note' (số ít)
        ]);

        // Gửi thông báo cho admin/staff
        $this->sendAdminNotification(
            'Đơn hàng mới #' . $order->id,
            'Khách hàng ' . $order->user->name . ' đã đặt đơn hàng mới với tổng giá trị ' . number_format($order->total_price) . 'đ',
            '/admin/orders/' . $order->id,
            'order_new'
        );
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Kiểm tra xem status có thay đổi không
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            // Map status sang message tiếng Việt
            $statusMessages = [
                'pending' => 'Đơn hàng đang chờ xác nhận',
                'confirmed' => 'Đơn hàng đã được xác nhận',
                'processing' => 'Đơn hàng đang được chuẩn bị',
                'ready' => 'Đơn hàng đã sẵn sàng',
                'shipping' => 'Đơn hàng đang được giao',
                'delivered' => 'Đơn hàng đã được giao thành công',
                'completed' => 'Đơn hàng đã hoàn thành',
                'cancelled' => 'Đơn hàng đã bị hủy',
            ];

            // Ưu tiên lấy ghi chú từ Controller truyền sang (qua thuộc tính tạm status_notes)
            // Nếu không có thì lấy tin nhắn mặc định từ mảng trên
            $notes = $order->status_notes ?? ($statusMessages[$newStatus] ?? 'Trạng thái đơn hàng đã được cập nhật');

            // Tạo history record
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'notes' => $notes // Đã sửa thành 'note' (số ít)
            ]);

            // === GỬi THÔNG BÁO CHO KHÁCH HÀNG ===
            // Chỉ gửi thông báo cho các trạng thái quan trọng
            $notifiableStatuses = ['ready', 'shipping', 'delivered', 'completed', 'cancelled'];

            if (in_array($newStatus, $notifiableStatuses)) {
                $this->sendNotification($order, $newStatus, $statusMessages[$newStatus] ?? '');
            }

            // === GỬi THÔNG BÁO CHO ADMIN ===
            // Thông báo khi đơn hàng bị hủy
            if ($newStatus === 'cancelled') {
                // Hoàn lại tồn kho khi đơn bị hủy (chỉ hoàn 1 lần theo lần chuyển trạng thái)
                if ($oldStatus !== 'cancelled') {
                    $this->restoreStockForCancelledOrder($order);
                }

                $this->sendAdminNotification(
                    'Đơn hàng #' . $order->id . ' đã bị hủy',
                    'Khách hàng ' . $order->user->name . ' đã hủy đơn hàng #' . $order->id . '. Lý do: ' . ($notes ?? 'Không rõ'),
                    '/admin/orders/' . $order->id,
                    'order_cancelled'
                );
            }

            // Dọn dẹp thuộc tính tạm
            unset($order->status_notes);
        }
    }

    private function restoreStockForCancelledOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $items = $order->orderItems()->select(['product_id', 'quantity'])->get();
            if ($items->isEmpty()) {
                return;
            }

            $productIds = $items->pluck('product_id')->unique()->values();
            $productsById = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $productsById->get($item->product_id);
                if (!$product) {
                    continue;
                }

                $product->stock = (int) $product->stock + (int) $item->quantity;
                if ($product->stock > 0) {
                    $product->status = 'in_stock';
                }
                $product->save();
            }
        });
    }

    /**
     * Gửi thông báo cho khách hàng
     */
    protected function sendNotification($order, $status, $message)
    {
        $notificationMessages = [
            'ready' => $order->delivery_method === 'pickup'
                ? 'Đơn hàng #' . $order->id . ' đã sẵn sàng! Bạn có thể đến cửa hàng lấy hàng.'
                : 'Đơn hàng #' . $order->id . ' đã được chuẩn bị xong và sắp được giao.',
            'shipping' => 'Đơn hàng #' . $order->id . ' đang trên đường giao đến bạn. Vui lòng chuẩn bị nhận hàng!',
            'delivered' => 'Đơn hàng #' . $order->id . ' đã được giao thành công. Cảm ơn bạn đã mua hàng!',
            'completed' => 'Đơn hàng #' . $order->id . ' đã hoàn thành. Hẹn gặp lại bạn!',
            'cancelled' => 'Đơn hàng #' . $order->id . ' đã bị hủy. Nếu có thắc mắc, vui lòng liên hệ chúng tôi.',
        ];

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_status',
            'message' => $notificationMessages[$status] ?? $message,
            'link' => '/account/orders/' . $order->id,
            'is_read' => false
        ]);
    }

    /**
     * Gửi thông báo cho admin/staff
     */
    protected function sendAdminNotification($title, $message, $link, $type = 'order')
    {
        // Lấy tất cả admin và staff
        $adminUsers = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['admin', 'staff']);
        })->get();

        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'message' => $message,
                'link' => $link,
                'is_read' => false
            ]);
        }
    }
}
