<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\RoleResource;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Services\Impl\RoleService;

class RoleController extends BaseController
{
    protected $roleService;
    protected $resource = \App\Http\Resources\RoleResource::class;
    public function __construct(
        RoleService $roleService,
    )
    {
        parent::__construct($roleService);
    }

    protected function getStoreRequest(): string
    {
        return StoreRequest::class;
    }

    protected function getUpdateRequest(): string
    {
        return UpdateRequest::class;
    }
    
}