<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    
    public static function success(array $data = [], string $message = '', int $code = 200) {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'timestamp' => now()
        ];
    }
    
    public static function error(array $errors = [], string $message = '', int $code = 400) {
        return [
            'status' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
            'timestamp' => now()
        ];
    }

    public static function message(string $message = '', int $code = 200) {
        return [
            'status' => $code === 200,
            'message' => $message,
            'code' => $code,
            'timestamp' => now()
        ];
    }
    
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}