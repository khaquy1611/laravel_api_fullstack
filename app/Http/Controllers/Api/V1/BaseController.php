<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

abstract class BaseController extends Controller
{
    protected $service;
    protected $resource;

    abstract protected function getStoreRequest() : string;
    abstract protected function getUpdateRequest() : string;
    
    public function __construct($service)
    {
        $this->service = $service;
    }
    public function store(Request $request) {
        $validator =  app($this->getStoreRequest());
        $storeRequest = $validator->validate($validator->rules());
        $result = $this->service->create($request);
       if ($result['flag']) {
        $objectResource = new $this->resource($result['data'])->toArray($request);
        return ApiResource::success($objectResource, 'Tạo mới dữ liệu thành công', Response::HTTP_OK);
       }
       return ApiResource::error($result, 'Tạo mới dữ liệu thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}