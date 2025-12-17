<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ShippingAddress;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request, VoucherService $voucherService)
    {
        $user = Auth::user();
        $addresses = ShippingAddress::where('user_id', $user->id)->get();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.firstImage'])
            ->get();
        $defaultAddress = ShippingAddress::where('user_id', $user->id)->where('default', 1)->first();
        $subtotal_price = 0;
        $totalQuantity = 0;

        foreach ($cartItems as $item) {
            $subtotal_price += $item->product->price * $item->quantity;
            $totalQuantity += $item->quantity;
        }

        $voucher_code = null;
        $voucher_error = null;
        $discount_amount = 0;

        $showVouchers = (config('site_settings.client_show_vouchers', '1') === '1');

        $shippingFeeConfig = (float) config('site_settings.shipping_fee', 30000);
        $freeShippingThreshold = (float) config('site_settings.free_shipping_threshold', 500000);

        if ($showVouchers) {
            $candidateCode = $request->string('voucher_code')->trim()->toString();
            if ($candidateCode !== '') {
                $voucher_code = strtoupper($candidateCode);
                try {
                    $result = $voucherService->validateAndCalculate($voucher_code, (int) $user->id, (float) $subtotal_price);
                    $discount_amount = (float) $result['discount'];
                } catch (\Throwable $e) {
                    $voucher_error = $e->getMessage();
                    $discount_amount = 0;
                    $voucher_code = null;
                }
            }
        }

        // Phí vận chuyển (miễn phí nếu đơn hàng >= ngưỡng)
        $shippingFee = $subtotal_price >= $freeShippingThreshold ? 0 : $shippingFeeConfig;

        // Tổng cộng
        $total_price = $subtotal_price - $discount_amount + $shippingFee;

        return view('client.pages.checkout', compact(
            'addresses',
            'defaultAddress',
            'cartItems',
            'subtotal_price',
            'totalQuantity',
            'discount_amount',
            'shippingFee',
            'total_price',
            'voucher_code',
            'voucher_error'
        ));
    }
}
