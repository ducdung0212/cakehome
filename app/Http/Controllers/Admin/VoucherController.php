<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pages.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.pages.vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'used_per_user_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_order_value'] = $validated['min_order_value'] ?? 0;
        $validated['is_active'] = (bool) ($validated['is_active'] ?? true);

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Tạo voucher thành công!');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.pages.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id,
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'used_per_user_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_order_value'] = $validated['min_order_value'] ?? 0;
        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Cập nhật voucher thành công!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:vouchers,id'
        ]);

        $voucher = Voucher::findOrFail($request->id);
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Đã xóa voucher.');
    }
}
