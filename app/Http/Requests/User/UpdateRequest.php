<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'id' => 'required|exists:users,id',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->route('user') . '',
            'birthday' => 'required|date|before:today',
            'publish' => 'gt:0',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif|max:5120' 
        ];
    }

    public function messages() : array {
        return [
            'name.required' => 'Bạn phải nhập tên người dùng (*)',
            'email.email' => 'Định dạng email không đúng (*)',
            'email.required' => 'Bạn phải nhập email (*)',         
            'email.unique' => 'Email đã tồn tại trong hệ thống (*)',
            'birthday.required' => 'Bạn phải nhập ngày sinh (*)',
            'birthday.date' => 'Ngày sinh không đúng định dạng ngày tháng (*)',
            'publish.gt' => 'Trường publish phải được chọn (*)',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => $this->route('user')
        ]);
    }
}