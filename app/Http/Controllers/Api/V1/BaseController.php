<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;

abstract class BaseController extends Controller
{
    protected $service;
    protected $resource;

    abstract protected function getStoreRequest() : string;
    abstract protected function getUpdateRequest() : string;
    abstract protected function getDeleteRequest() : string;
    abstract protected function getDeleteMultipleRequest() : string;
    
    public function __construct($service)
    {
        $this->service = $service;
    }

    public function all(Request $request) {
        try {
            $data = $this->service->paginate($request, 'list');
            $resource = $this->resource::collection($data);
            return ApiResource::success($resource, 'Lấy dữ liệu danh sách thành công', Response::HTTP_OK);   
        }catch( \Exception $e) {
            return ApiResource::message("Error " .$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function index(Request $request) {
        try {
            $data = $this->service->paginate($request);
            $data->through(function ($item) {
                return new $this->resource($item);
            });
            return ApiResource::success($data, 'Phân trang danh sách thành công', Response::HTTP_OK); 
        }catch( \Exception $e) {
            return ApiResource::message("Error " .$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
      
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
        try {
            $this->handleRequest($this->getUpdateRequest());
            $result = $this->service->save($request, $id);
            if ($result['flag']) {
            $objectResource = new $this->resource($result['data'])->toArray($request);
            return ApiResource::success($objectResource, 'Cập nhập dữ liệu thành công', Response::HTTP_OK);
            }
        }catch(AuthorizationException $e) {
            return ApiResource::message($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
        catch(\Exception $e) {
            return ApiResource::error($result['error'], 'Có lỗi xảy ra trong khi cập nhập dữ liệu , vui lòng cập nhập lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(mixed $id = null) {
        $this->handleRequest($this->getDeleteRequest());
        $result = $this->service->delete($id);
        if ($result['flag']) {
            return ApiResource::message('Xóa dữ liệu thành công', Response::HTTP_OK);
        }
        return ApiResource::error($result['error'], 'Có lỗi xảy ra trong khi xóa dữ liệu , vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function deleteMultiple(Request $request) {
        $this->handleRequest($this->getDeleteMultipleRequest());
        $result = $this->service->deleteMultiple($request);
        if ($result['flag']) {
            return ApiResource::message($result['deletedCount'] . ' bản ghi đã được xóa thành công', Response::HTTP_OK);
        }
        return ApiResource::error($result['error'],'Có lỗi xảy ra trong khi xóa dữ liệu , vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function show($id = null) {
        try {
            $data = $this->service->show($id);
            if (!$data) {
               return ApiResource::message('Không tìm thấy dữ liệu', Response::HTTP_NOT_FOUND);
            }
            $objectResource = new $this->resource($data);
            return ApiResource::success($objectResource, 'Lấy dữ liệu đơn thành công', Response::HTTP_OK);        
        }catch(Exception $e) {
            return ApiResource::message('Error ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);  
        }
     
    }
}