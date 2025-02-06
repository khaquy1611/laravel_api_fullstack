<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;

class CreatModulePermissionRequest extends BaseRequest
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
            'model' => 'required',
            
        ];
    }
    public function messages() : array {
        return [
            'name.required' => 'Bạn phải nhập tên model (*)',
        ];
    }
}