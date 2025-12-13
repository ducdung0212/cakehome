<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email'=>'email|required|exists:users,email'
        ];
    }
    public function attribute(): array 
    {
            return [
                'email' =>'Email'
            ];
    }
    public function messages(): array{
        return [
            'email' => ':attribute không hợp lệ',
            'required' =>'Vui lòng nhập :attribute',
            'exists' =>":attribute không tồn tại trong hệ thống"
        ];
    }
}
