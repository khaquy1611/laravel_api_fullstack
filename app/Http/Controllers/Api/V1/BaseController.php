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
    abstract protected function getDeleteRequest() : string;
    
    public function __construct($service)
    {
        $this->service = $service;
    }

    private function handleRequest(string $requestAction = '') {
        $validator =  app($requestAction);
        $validator->validate($validator->rules());
    }

    public function store(Request $request) {
        $this->handleRequest($this->getStoreRequest());
        $result = $this->service->save($request);
        if ($result['flag']) {
        $objectResource = new $this->resource($result['data'])->toArray($request);
        return ApiResource::success($objectResource, 'Tạo mới dữ liệu thành công', Response::HTTP_OK);
        }
        return ApiResource::error($result['error'], 'Tạo mới dữ liệu thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function update(Request $request, mixed $id = null) {
        $this->handleRequest($this->getUpdateRequest());
        $result = $this->service->save($request, $id);
        if ($result['flag']) {
            $objectResource = new $this->resource($result['data'])->toArray($request);
            return ApiResource::success($objectResource, 'Cập nhập dữ liệu thành công', Response::HTTP_OK);
        }
        return ApiResource::error($result['error'], 'Có lỗi xảy ra trong khi cập nhập dữ liệu , vui lòng cập nhập lại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function destroy(mixed $id = null) {
        $this->handleRequest($this->getDeleteRequest());
        $result = $this->service->delete($id);
        if ($result['flag']) {
            return ApiResource::message('Xóa dữ liệu thành công', Response::HTTP_OK);
        }
        return ApiResource::error($result['error'], 'Có lỗi xảy ra trong khi xóa dữ liệu , vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}