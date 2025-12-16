<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\ShippingAddress;
use App\Models\VoucherUsage;
use App\Models\Notification;
use App\Models\User;
use App\Services\MoMoPaymentService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Xử lý đặt hàng
     */
    public function placeOrder(PlaceOrderRequest $request, VoucherService $voucherService)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // 1. Lấy giỏ hàng của user
            $cartItems = CartItem::where('user_id', $user->id)
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống!');
            }

            // 2. Tính toán giá
            $subtotal_price = 0;
            foreach ($cartItems as $item) {
                $subtotal_price += $item->product->price * $item->quantity;
            }

            $discount_amount = 0;
            $voucherId = null;
            $voucher = null;

            $showVouchers = (config('site_settings.client_show_vouchers', '1') === '1');
            $shippingFeeConfig = (float) config('site_settings.shipping_fee', 30000);
            $freeShippingThreshold = (float) config('site_settings.free_shipping_threshold', 500000);

            if ($showVouchers) {
                $voucherCode = $request->string('voucher_code')->trim()->toString();
                if ($voucherCode !== '') {
                    $voucherCode = strtoupper($voucherCode);
                    $result = $voucherService->validateAndCalculateForUpdate($voucherCode, (int) $user->id, (float) $subtotal_price);
                    $voucher = $result['voucher'];
                    $discount_amount = (float) $result['discount'];
                    $voucherId = (string) $voucher->id;
                }
            }

            // Phí ship: Miễn phí nếu pickup hoặc đơn >= ngưỡng
            $deliveryMethod = $request->delivery_method ?? 'delivery';
            if ($deliveryMethod === 'pickup') {
                $shippingFee = 0; // Miễn phí khi nhận tại cửa hàng
            } else {
                $shippingFee = $subtotal_price >= $freeShippingThreshold ? 0 : $shippingFeeConfig;
            }

            $total_price = $subtotal_price - $discount_amount + $shippingFee;

            // 3. Xử lý địa chỉ giao hàng
            $shippingAddressId = null;

            if ($deliveryMethod === 'delivery') {
                // Chỉ cần địa chỉ khi giao hàng tận nơi
                if ($request->address_type === 'saved') {
                    $shippingAddressId = $request->saved_address_id;
                } else {
                    // Tạo địa chỉ mới
                    $newAddress = ShippingAddress::create([
                        'user_id' => $user->id,
                        'full_name' => $request->receiver_name,
                        'phone_number' => $request->receiver_phone,
                        'address' => $request->address,
                        'ward' => $request->ward,
                        'district' => $request->district,
                        'province' => $request->province,
                        'default' => $request->save_address ? 1 : 0,
                    ]);
                    $shippingAddressId = $newAddress->id;

                    // Nếu set làm mặc định, bỏ default của các địa chỉ khác
                    if ($request->save_address) {
                        ShippingAddress::where('user_id', $user->id)
                            ->where('id', '!=', $newAddress->id)
                            ->update(['default' => 0]);
                    }
                }
            }

            // 4. Xử lý thời gian giao hàng
            $deliveryAt = null;
            if ($deliveryMethod === 'delivery') {
                // Chỉ xử lý thời gian khi giao hàng tận nơi
                if ($request->delivery_now) {
                    // Nhận ngay = hiện tại + 1 tiếng (thời gian chuẩn bị tối thiểu)
                    $deliveryAt = now()->addHour();
                } elseif ($request->delivery_at) {
                    $deliveryAt = Carbon::parse($request->delivery_at);
                }
            }
            // Nếu pickup thì delivery_at = null

            // 5. Tạo đơn hàng (Observer sẽ tự động tạo history)
            $order = Order::create([
                'user_id' => $user->id,
                'subtotal_price' => $subtotal_price,
                'total_price' => $total_price,
                'voucher_id' => $voucherId,
                'discount_amount' => $discount_amount,
                'status' => 'pending',
                'shipping_address_id' => $shippingAddressId,
                'delivery_method' => $deliveryMethod,
                'delivery_at' => $deliveryAt,
                'notes' => $request->notes,
            ]);

            // OrderStatusHistory đã được tạo tự động bởi OrderObserver

            // 6. Tạo các order items từ cart
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            // 6.1 Lưu lượt sử dụng voucher
            if ($voucher) {
                VoucherUsage::create([
                    'voucher_id' => $voucher->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);

                $voucher->increment('used_count');
            }

            // 7. Xử lý thanh toán
            $paymentMethod = $request->payment_method === 'cod' ? 'cash' : 'momo';

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'amount' => $total_price,
                'status' => $paymentMethod === 'cash' ? 'pending' : 'pending',
            ]);

            // 8. Xóa giỏ hàng sau khi đặt hàng thành công
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            // Xử lý theo phương thức thanh toán
            if ($paymentMethod === 'momo') {
                // Chuyển hướng đến MoMo để thanh toán
                return $this->processMoMoPayment($order, $payment);
            } else {
                // COD - Thanh toán khi nhận hàng
                // TODO: Gửi email xác nhận đơn hàng
                // TODO: Gửi thông báo cho admin

                return redirect()
                    ->route('account.orders')
                    ->with('success', 'Đặt hàng thành công! Đơn hàng của bạn đang được xử lý. Vui lòng thanh toán khi nhận hàng.');
            }
        } catch (\RuntimeException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Place Order Error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!');
        }
    }

    /**
     * Xử lý thanh toán MoMo
     */
    protected function processMoMoPayment($order, $payment, $isRetry = false)
    {
        try {
            $momoService = new MoMoPaymentService();

            // Tạo orderId unique cho mỗi lần thanh toán
            // Format: {order_id}_{payment_id}_{timestamp}
            // Điều này đảm bảo mỗi request luôn unique, kể cả đơn mới
            $orderId = $order->id . '_' . $payment->id . '_' . time();

            $orderInfo = "Thanh toán đơn hàng #" . $order->id . " - CakeHome";
            $result = $momoService->createPayment(
                $orderId,
                (int)$order->total_price,
                $orderInfo
            );

            if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                // Lưu transaction ID
                $payment->update([
                    'transaction_id' => $result['requestId']
                ]);

                // Chuyển hướng đến trang thanh toán MoMo
                return redirect($result['payUrl']);
            } else {
                // Lỗi từ MoMo
                $payment->update(['status' => 'failed']);

                return redirect()
                    ->route('account.orders')
                    ->with('error', 'Không thể kết nối đến MoMo. Vui lòng chọn phương thức thanh toán khác. Mã lỗi: ' . ($result['message'] ?? 'Unknown'));
            }
        } catch (\Exception $e) {
            Log::error('MoMo Payment Processing Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('account.orders')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán MoMo. Vui lòng thử lại!');
        }
    }

    /**
     * Xử lý callback return từ MoMo (Khách quay lại sau khi thanh toán)
     */
    public function momoReturn(Request $request)
    {
        try {
            $orderId = $request->orderId;
            $resultCode = $request->resultCode;
            $message = $request->message;

            // Parse orderId: Nếu có timestamp (format: 123_1234567890), lấy phần trước dấu _
            $realOrderId = $orderId;
            if (strpos($orderId, '_') !== false) {
                $realOrderId = explode('_', $orderId)[0];
            }

            $order = Order::find($realOrderId);
            if (!$order) {
                return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng!');
            }

            $payment = Payment::where('order_id', $realOrderId)->first();

            if ($resultCode == 0) {
                // Thanh toán thành công
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $request->transId,
                    'paid_at' => now()
                ]);

                $order->update(['status' => 'processing']);

                // Gửi thông báo cho admin về thanh toán MoMo thành công
                $this->sendAdminNotification(
                    'Thanh toán MoMo thành công',
                    'Đơn hàng #' . $realOrderId . ' đã được thanh toán thành công qua MoMo với số tiền ' . number_format($order->total_price) . 'đ',
                    '/admin/orders/' . $realOrderId,
                    'payment_momo_success'
                );

                return redirect()
                    ->route('account.orders')
                    ->with('success', 'Thanh toán thành công! Đơn hàng #' . $realOrderId . ' đang được xử lý.');
            } else {
                // Thanh toán thất bại hoặc bị hủy
                $payment->update(['status' => 'failed']);

                return redirect()
                    ->route('account.orders')
                    ->with('error', 'Thanh toán thất bại: ' . $message . '. Đơn hàng vẫn được giữ, bạn có thể thanh toán lại sau.');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Return Error', [
                'request' => $request->all(),
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('home')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán!');
        }
    }

    /**
     * Xử lý IPN/Notify từ MoMo (Server-to-server)
     */
    public function momoNotify(Request $request)
    {
        try {
            Log::info('MoMo IPN Received', $request->all());

            $momoService = new MoMoPaymentService();

            // Xác thực chữ ký
            if (!$momoService->verifySignature($request->all())) {
                Log::warning('MoMo Invalid Signature', $request->all());
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            $orderId = $request->orderId;
            $resultCode = $request->resultCode;
            $transId = $request->transId;

            // Parse orderId: Nếu có timestamp (format: 123_1234567890), lấy phần trước dấu _
            $realOrderId = $orderId;
            if (strpos($orderId, '_') !== false) {
                $realOrderId = explode('_', $orderId)[0];
            }

            $order = Order::find($realOrderId);
            $payment = Payment::where('order_id', $realOrderId)->first();

            if ($order && $payment) {
                if ($resultCode == 0) {
                    // Thanh toán thành công
                    $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $transId,
                        'paid_at' => now()
                    ]);

                    $order->update(['status' => 'processing']);

                    // TODO: Gửi email xác nhận
                    // TODO: Thông báo cho admin

                } else {
                    // Thanh toán thất bại
                    $payment->update(['status' => 'failed']);
                }
            }

            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            Log::error('MoMo IPN Error', [
                'request' => $request->all(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Thanh toán lại đơn hàng MoMo chưa thanh toán
     */
    public function retryPayment($id)
    {
        try {
            $order = Order::where('id', $id)
                ->where('user_id', Auth::id())
                ->with('payment')
                ->firstOrFail();

            // Kiểm tra điều kiện để thanh toán lại
            if (!$order->payment || $order->payment->payment_method !== 'momo') {
                return redirect()->back()->with('error', 'Đơn hàng này không sử dụng phương thức thanh toán MoMo!');
            }

            if ($order->payment->status === 'completed') {
                return redirect()->back()->with('error', 'Đơn hàng đã được thanh toán!');
            }

            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return redirect()->back()->with('error', 'Không thể thanh toán lại đơn hàng này!');
            }

            // Reset payment status về pending trước khi retry
            $order->payment->update(['status' => 'pending']);

            // Tạo request thanh toán mới với flag retry = true
            return $this->processMoMoPayment($order, $order->payment, true);
        } catch (\Exception $e) {
            Log::error('Retry Payment Error', [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
        }
    }

    /**
     * Hủy đơn hàng
     */
    public function cancelOrder($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('id', $id)
                ->where('user_id', Auth::id())
                ->with('payment')
                ->firstOrFail();

            // Kiểm tra điều kiện hủy đơn
            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng đang chờ xác nhận hoặc đã xác nhận!');
            }

            // Kiểm tra nếu đã thanh toán MoMo
            if (
                $order->payment &&
                $order->payment->payment_method === 'momo' &&
                $order->payment->status === 'completed'
            ) {
                return redirect()->back()->with('error', 'Đơn hàng đã thanh toán qua MoMo không thể tự hủy. Vui lòng liên hệ shop để được hỗ trợ hoàn tiền!');
            }

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            // Cập nhật payment status
            if ($order->payment && $order->payment->status !== 'completed') {
                $order->payment->update(['status' => 'failed']);
            }

            DB::commit();

            return redirect()->route('account.orders')->with('success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Cancel Order Error', [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại!');
        }
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
