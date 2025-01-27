<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RefreshTokenRepository;

class RefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $refreshTokenRepository;
    public function __construct() {
        $this->refreshTokenRepository = app(RefreshTokenRepository::class);
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
            'refresh_token' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $refreshTokenValue = $this->input('refresh_token');
            $refreshToken = $this->refreshTokenRepository->findRefreshTokenValid($refreshTokenValue);
            if (!$refreshToken) {
                $validator->errors()->add('refresh_token', 'Refresh token đã hết hạn hoặc không tồn tại');
            }
        });
        
    }
}