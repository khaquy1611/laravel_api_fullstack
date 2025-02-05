<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
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
            'name' => 'required',
            'password' => 'required|min:6|max:24',
            'email' => 'required|email|unique:users',
            'birthday' => 'required|date|before:today',
            'publish' => 'gt:0'
        ];
    }
    
    public function messages() : array {
        return [
            'name.required' => 'Bạn phải nhập tên người dùng (*)',
            'email.email' => 'Định dạng email không đúng (*)',
            'email.required' => 'Bạn phải nhập email (*)',         
            'email.unique' => 'Email đã tồn tại trong hệ thống (*)',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự (*)',
            'password.max' => 'Mật khẩu phải có tối đa 24 ký tự (*)',
            'birthday.required' => 'Bạn phải nhập ngày sinh (*)',
            'birthday.date' => 'Ngày sinh không đúng định dạng ngày tháng (*)',
            'publish.gt' => 'Trường publish phải được chọn (*)',
            
        ];
    }
}