<?php

namespace App\Repositories;
use App\Models\User;

class UserRepository  extends BaseRepository
{
    private $model;
    public function __construct(User $model)
    {
       parent::__construct($model);
       $this->model = $model;
    }
}