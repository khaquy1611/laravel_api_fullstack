<?php

namespace App\Repositories;
use App\Models\Permission;

class PermissionRepository  extends BaseRepository
{
    private $model;
    public function __construct(Permission $model)
    {
       parent::__construct($model);
       $this->model = $model;
    }
}