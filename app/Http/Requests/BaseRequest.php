<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => 422
        ], 422));
    }
}