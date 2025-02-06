<?php

namespace App\Http\Requests\Role;

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
            'publish' => 'gt:0',
            'permisions' => 'required|array',
            'permisions.*' => 'exists:permisions,id' // kiểm tra xem id permisions có tồn tại trong bảng permisions không
        ];
    }
    
    public function messages() : array {
        return [
            'name.required' => 'Bạn phải nhập tên (*)',
            'publish.gt' => 'Trường publish phải được chọn (*)',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => $this->route('role')
        ]);
    }
}