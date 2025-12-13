<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token'=>'required',
            'password'=> 'required|min:6|confirmed'
        ];
    }
    public function attributes(): array
    {
        return [
            'token' =>'Token',
            'password' =>'Mật khẩu'
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute',
            'min' =>':attribute phải có ít nhất 6 kí tự',
            'confirmed' => 'Xác nhận :attribute không khớp',
            'exists' =>":attribute không tồn tại trong hệ thống",
            'email' => ':attribute không hợp lệ'
        ];
    }
}
