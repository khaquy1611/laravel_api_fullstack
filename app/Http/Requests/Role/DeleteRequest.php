<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use App\Repositories\RoleRepository;

class DeleteRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $roleRepository;
    public function __construct() {
        $this->roleRepository = app(RoleRepository::class);
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
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $roleId = $this->route('role');
            $role = $this->roleRepository->findById($roleId);
            if (!$role || !$roleId) {
                $validator->errors()->add('role', 'Vai trò không tồn tại.');
            }
        });
        
    }
}