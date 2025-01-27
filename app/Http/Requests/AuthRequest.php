<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class AuthRequest extends BaseRequest
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
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    public function messages() : array {
        return [
            'email.required' => 'Bạn phải nhập email (*)',
            'email.email' => 'Định dạng email không đúng (*)',
            'password.required' => 'Bạn phải nhập mật khẩu (*)',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự (*)',
        ];
    }

}