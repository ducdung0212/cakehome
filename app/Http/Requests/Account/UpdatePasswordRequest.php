<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
    }
    public function attributes(): array
    {
        return [
            'current_password' => "Mật khẩu hiện tại",
            'password' => "Mật khẩu mới"
        ];
    }
    public function messages(): array
    {
        return [
            'required' => ":attribute bắt buộc phải nhập!",
            'min' => ":attribute phải có ít nhất :min kí tự",
            'confirmed' => ":attribute không khớp"
        ];
    }
}
