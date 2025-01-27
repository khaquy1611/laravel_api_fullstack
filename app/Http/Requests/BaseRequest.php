<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator): void
    {
        $resource = ApiResource::error($validator->errors()->toArray(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException(response()->json($resource, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}