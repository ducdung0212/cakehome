<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlaceOrderRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('voucher_code') && is_string($this->voucher_code)) {
            $this->merge([
                'voucher_code' => strtoupper(trim($this->voucher_code)),
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check(); // Chỉ cho phép user đã đăng nhập
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // Phương thức giao hàng
            'delivery_method' => 'required|in:delivery,pickup',

            // Địa chỉ giao hàng: CHỈ BẮT BUỘC KHI PHƯƠNG THỨC LÀ 'DELIVERY'
            'address_type' => 'required_if:delivery_method,delivery|in:saved,new',

            // Các ràng buộc con chỉ chạy nếu address_type có giá trị (tức là khi delivery)
            'saved_address_id' => 'required_if:address_type,saved|nullable|exists:shipping_addresses,id',
            'receiver_name' => 'required_if:address_type,new|nullable|string|max:255',
            'receiver_phone' => 'required_if:address_type,new|nullable|string|max:20',
            'province' => 'required_if:address_type,new|nullable|string',
            'district' => 'required_if:address_type,new|nullable|string',
            'ward' => 'required_if:address_type,new|nullable|string',
            'address' => 'required_if:address_type,new|nullable|string|max:500',
            'save_address' => 'nullable|boolean',

            // Thời gian giao hàng - chỉ bắt buộc khi delivery_method = 'delivery'
            'delivery_now' => 'nullable|boolean',

            // Ghi chú và thanh toán
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,momo',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ];

        // Chỉ validate delivery_at khi giao hàng tận nơi
        if ($this->delivery_method === 'delivery') {
            $rules['delivery_at'] = [
                'required_without:delivery_now',
                'nullable',
                'date_format:Y-m-d H:i',
                'after:' . now()->addHours(2)->format('Y-m-d H:i'),

                // Custom validation cho giờ hành chính
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        try {
                            $time = Carbon::parse($value);
                            // Kiểm tra giờ phải từ 8h sáng đến 8h tối
                            if ($time->hour < 8 || $time->hour >= 20) {
                                $fail('Chúng tôi chỉ giao hàng từ 8:00 đến 20:00.');
                            }
                        } catch (\Exception $e) {
                            $fail('Thời gian giao hàng không hợp lệ.');
                        }
                    }
                },
            ];
        }

        return $rules;
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'delivery_at.required_without' => 'Vui lòng chọn thời gian giao hàng hoặc chọn "Giao ngay".',
            'delivery_at.after' => 'Bạn cần đặt trước ít nhất 2 tiếng để shop chuẩn bị.',
            'delivery_at.date_format' => 'Định dạng thời gian không hợp lệ.',

            'address_type.required_if' => 'Vui lòng chọn loại địa chỉ.',

            'saved_address_id.required_if' => 'Vui lòng chọn địa chỉ giao hàng.',
            'saved_address_id.exists' => 'Địa chỉ giao hàng không tồn tại.',

            'receiver_name.required_if' => 'Vui lòng nhập tên người nhận.',
            'receiver_phone.required_if' => 'Vui lòng nhập số điện thoại người nhận.',
            'province.required_if' => 'Vui lòng chọn tỉnh/thành phố.',
            'district.required_if' => 'Vui lòng chọn quận/huyện.',
            'ward.required_if' => 'Vui lòng chọn phường/xã.',
            'address.required_if' => 'Vui lòng nhập địa chỉ cụ thể.',

            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
