<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\VoucherService;


class CartItemController extends Controller
{
    public function index(Request $request, VoucherService $voucherService)
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with(['product.firstImage'])
            ->paginate(8);

        // Tính toán tóm tắt đơn hàng
        $allCartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $subtotal_price = 0;
        $totalQuantity = 0;

        foreach ($allCartItems as $item) {
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
                if (!Auth::check()) {
                    $voucher_error = 'Vui lòng đăng nhập để sử dụng mã giảm giá.';
                } else {
                    $voucher_code = strtoupper($candidateCode);
                    try {
                        $result = $voucherService->validateAndCalculate($voucher_code, (int) Auth::id(), (float) $subtotal_price);
                        $discount_amount = (float) $result['discount'];
                    } catch (\Throwable $e) {
                        $voucher_error = $e->getMessage();
                        $discount_amount = 0;
                        $voucher_code = null;
                    }
                }
            }
        }

        // Phí vận chuyển (miễn phí nếu đơn hàng >= ngưỡng)
        $shippingFee = $subtotal_price >= $freeShippingThreshold ? 0 : $shippingFeeConfig;

        // Tổng cộng
        $total_price = $subtotal_price - $discount_amount + $shippingFee;

        return view('client.pages.cart', compact(
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
    public function addItemToCart(Request $request)
    {
        $request->merge(['quantity' => (int)$request->quantity]);
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        $product = Product::findOrFail($request->product_id);
        if ($request->quantity > $product->stock) {
            return response()->json(['message' => 'Số lượng vượt quá tồn kho'], 400);
        }
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();
            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->product_id]))
                $cart[$request->product_id]['quantity'] += $request->quantity;
            else {
                $cart[$request->product_id] = [
                    'product_id' => $request->product_id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $request->quantity,
                    'stock' => $product->stock,
                    'image' => $product->firstImage()->image ?? 'images/no-image-product.png'
                ];
            }
            session()->put('cart', $cart);
            $cartCount = count($cart);
        }
        return response()->json([
            'success'    => true,
            'message'    => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_count' => $cartCount
        ]);
    }
    // Xóa khỏi giỏ
    public function remove(Request $request)
    {
        $productId = $request->product_id;

        // Xóa trong DB
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)->delete();
            $count = CartItem::where('user_id', Auth::id())->count();

            // Tính toán lại tổng tiền
            $cartSummary = $this->calculateCartSummary();
        }
        // Xóa trong Session
        else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
            $count = count($cart);
            $cartSummary = ['subtotal' => 0, 'total' => 0, 'totalQuantity' => 0, 'discount' => 0, 'shippingFee' => 0];
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm!',
            'cart_count' => $count,
            'cart_summary' => $cartSummary
        ]);
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        // Kiểm tra tồn kho
        $product = Product::findOrFail($productId);
        if ($quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho!'
            ], 400);
        }

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }

            $count = CartItem::where('user_id', Auth::id())->count();

            // Tính toán lại tổng tiền
            $cartSummary = $this->calculateCartSummary();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                session()->put('cart', $cart);
            }
            $count = count($cart);
            $cartSummary = ['subtotal' => 0, 'total' => 0, 'totalQuantity' => 0, 'discount' => 0, 'shippingFee' => 0];
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng!',
            'cart_count' => $count,
            'cart_summary' => $cartSummary
        ]);
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng!',
            'cart_count' => 0
        ]);
    }

    // Helper: Tính toán tóm tắt giỏ hàng
    private function calculateCartSummary()
    {
        $allCartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($allCartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
            $totalQuantity += $item->quantity;
        }

        $discount = 0;
        $shippingFee = $subtotal >= 500000 ? 0 : 30000;
        $total = $subtotal - $discount + $shippingFee;

        return [
            'subtotal' => $subtotal,
            'totalQuantity' => $totalQuantity,
            'discount' => $discount,
            'shippingFee' => $shippingFee,
            'total' => $total
        ];
    }
}
