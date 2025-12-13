<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;


class CartItemController extends Controller
{
    public function index(){
        $cartItems=CartItem::where('user_id',Auth::id())
        ->with(['product.firstImage'])
        ->paginate(8);
        return view('client.pages.cart',compact('cartItems'));
    }
    public function addItemToCart(Request $request){
        $request->merge(['quantity'=>(int)$request->quantity]);
        $request->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1'
        ]);
        $product=Product::findOrFail($request->product_id);
        if($request->quantity>$product->stock){
            return response()->json(['message'=>'Số lượng vượt quá tồn kho'],400);
        }
        if(Auth::check()){
            $cartItem=CartItem::where('user_id',Auth::id())
            ->where('product_id',$request->product_id)
            ->first();
             if($cartItem){
                $cartItem->quantity+=$request->quantity;
                $cartItem->save();
            }
             else{
                CartItem::create([
                    'user_id'=>Auth::id(),
                    'product_id'=>$request->product_id,
                    'quantity'=>$request->quantity
                ]);
            }
            $cartCount=CartItem::where('user_id',Auth::id())->count();
        }
        else{
            $cart=session()->get('cart',[]);
            if(isset($cart[$request->product_id]))
                $cart[$request->product_id]['quantity']+=$request->quantity;
            else{
                $cart[$request->product_id]=[
                    'product_id'=>$request->product_id,
                    'name'=>$product->name,
                    'price'=>$product->price,
                    'quantity'=>$request->quantity,
                    'stock'=>$product->stock,
                    'image'=>$product->firstImage()->image??'images/no-image-product.png'
                ];
            }
            session()->put('cart',$cart);
            $cartCount=count($cart);
        }
       return response()->json([
            'success'    => true, 
            'message'    => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_count'=>$cartCount
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
        } 
        // Xóa trong Session
        else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
            $count = count($cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm!',
            'cart_count' => $count
            // Bạn có thể tính toán lại tổng tiền ở đây và trả về biến 'total_amount'
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
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                session()->put('cart', $cart);
            }
            $count = count($cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng!',
            'cart_count' => $count
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
}
