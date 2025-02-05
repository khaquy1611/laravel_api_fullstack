<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Determine if the permission is authorized to make this request.
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
            'id' => 'required|exists:permisions,id',
            'name' => 'required|regex:/^[a-z]+:[a-zA-Z]+$/|unique:permisions',
            'publish' => 'gt:0'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => $this->route('permission')
        ]);
    }
}