<?php

namespace App\Http\Requests\Permission;

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
            'name' => 'required|regex:/^[a-z]+:[a-zA-Z]+$/|unique:permisions',
            'publish' => 'gt:0',
            
        ];
    }
    public function messages() : array {
        return [
            'name.required' => 'Bạn phải nhập tên người dùng (*)',
            'publish.gt' => 'Trường publish phải được chọn (*)',
            
        ];
    }
}