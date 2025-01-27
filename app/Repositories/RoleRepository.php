<?php

namespace App\Repositories;
use App\Models\Role;

class RoleRepository  extends BaseRepository
{
    private $model;
    public function __construct(Role $model)
    {
       parent::__construct($model);
       $this->model = $model;
    }
}