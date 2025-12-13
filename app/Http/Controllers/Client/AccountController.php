<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AddressRequest;
use App\Http\Requests\Account\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\ShippingAddress;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('client.account.index', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('client.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:11',
            ]
        );

        $user = Auth::user();
        $user->update($request->only('name', 'phone_number'));

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function addresses()
    {
        $shippingAddresses = Auth::user()->shippingAddresses;
        return view('client.account.addresses', compact('shippingAddresses'));
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(10);
        return view('client.account.orders', compact('orders'));
    }

    public function changePassword()
    {
        return view('client.account.change-password');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
    public function addAddress(AddressRequest $request)
    {
        $validated = $request->validated();
        if($request->has('default')){
            ShippingAddress::where('user_id',Auth::id())->update(['default'=>0]);
        }
        ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'default' => $request->has('default') ? 1 : 0
        ]);

        return back()->with('success', 'Thêm địa chỉ mới thành công!');
    }
    public function deleteAddress($id)
    {
        ShippingAddress::where('user_id', Auth::id())->where('id',$id)->delete();
        return redirect()->back()->with('success', 'Đã xóa địa chỉ thành công!');
    }
    public function editAddress(AddressRequest $request, $id)
    {
        $validated = $request->validated();
        $address =  ShippingAddress::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
        $address->update([
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'default' => $request->has('default') ? 1 : 0
        ]);
        return back()->with('success','Đã cập nhật địa chỉ thành công!');
    }
    public function setDefaultAddress($id){
        $address=ShippingAddress::where('user_id',Auth::id())->where('id',$id)->firstOrFail();
        ShippingAddress::where('user_id',Auth::id())->update(['default'=>0]);
        $address->update(['default'=>1]);
        return back()->with('success','Đổi địa chỉ mặc định thành công! ');
    }
}
