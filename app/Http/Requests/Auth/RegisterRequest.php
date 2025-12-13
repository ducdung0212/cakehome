<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|regex:/^0[0-9]{9,10}$/|unique:users,phone_number',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Họ và tên',
            'email' => 'Email',
            'phone_number' => 'Số điện thoại',
            'password' => 'Mật khẩu',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute',
            'min' => ':attribute phải có ít nhất :min ký tự',
            'email' => ':attribute không hợp lệ',
            'unique' => ':attribute đã được sử dụng',
            'regex' => ':attribute không hợp lệ (10-11 số, bắt đầu bằng 0)',
            'confirmed' => 'Xác nhận :attribute không khớp',
        ];
    }
}
