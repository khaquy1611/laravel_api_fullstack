<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Repositories\UserRepository;

class DeleteRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $userRepository;
    public function __construct() {
        $this->userRepository = app(UserRepository::class);
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
            'id' => 'required|exists:users,id',
        ];
    }
    protected function prepareForValidation() {
        $this->merge([
            'id' => $this->route('user')
        ]);
    }
}