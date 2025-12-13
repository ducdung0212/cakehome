<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11',
        ];
    }
    public function attributes(): array
    {
        return [
            'name'=>"Họ và tên",
            'phone_number'=>"Số điện thoại"
        ];
    }
    public function messages(): array
    {
        return[
            'required'=>":attribute bắt buộc phải nhập",
            'max'=>":attribute có tối đa :max kí tự",
            'phone_number'=>":attribute có tối đa :max số"
        ];
    }
}
