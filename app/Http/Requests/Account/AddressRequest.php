<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:255'
        ];
    }
    public function attributes(): array
    {
        return [
            'full_name' => 'Họ và tên người nhận',
            'phone_number' => 'Số điện thoại',
            'province' => 'Tỉnh/Thành phố',
            'district' => 'Quận/Huyện',
            'ward' => 'Phường/Xã',
            'address' => 'Địa chỉ cụ thể'
        ];
    }
    public function messages(): array
    {
        return [
            'required' => ':attribute bắt buộc phải nhập!',
            'name.max' => ':attribute chứa tối đa :max kí tự!',
        ];
    }
}
