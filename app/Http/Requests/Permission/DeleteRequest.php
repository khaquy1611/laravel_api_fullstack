<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;
use App\Repositories\PermissionRepository;

class DeleteRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $permissionRepository;
    public function __construct() {
        $this->permissionRepository = app(PermissionRepository::class);
    }

    
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
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => $this->route('permission')
        ]);
    }
}