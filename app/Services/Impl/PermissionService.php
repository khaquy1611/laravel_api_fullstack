<?php

namespace App\Services\Impl;
use App\Services\Impl\BaseService;
use App\Repositories\PermissionRepository;
use App\Services\Interfaces\PermissionServiceInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class PermissionService extends BaseService implements PermissionServiceInterface
{
    protected $permissionRepository;
    protected $payload;
    public function __construct(PermissionRepository $permissionRepository)
    {
        parent::__construct($permissionRepository);
        $this->permissionRepository = $permissionRepository;
    }   

    protected function getSearchField() : array {
        return ['name'];
    }
    protected function getPerPage() : int {
        return 20;
    }
    protected function getSimpleFilter() : array {
        return ['publish'];
    }
    protected function getComplexFilter() : array {
        return ['id'];
    }
    protected function getDateFilter() : array {
        return ['created_at'];
    }
    protected function requestOnlyPayload(): array
    {
        return ['name', 'publish'];
    }
    protected function processPayload() {
        return $this;
    }
    public function createModulePermission($request) {
        DB::beginTransaction();
        try {
            $model = $request->input('model');
            $methods = ['all', 'index', 'store', 'update', 'destroy', 'deleteMultiple', 'show'];
            $permissions = [];
            foreach($methods as $action) {
            $payload = ['name' => "{$model}:{$action}", 'publish' => $request->input('publish')];
            
            if($this->permissionRepository->checkExists('name', $payload['name'])) continue;
            $data = $this->permissionRepository->create($payload);
            $permissions[] = $data;
            }
            DB::commit();
            return [
                'flag' => true,
                'permissions' => $permissions
            ];
        }catch(Exeption $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }  
        
    }
}