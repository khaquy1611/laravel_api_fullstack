<?php

namespace App\Repositories;
class BaseRepository
{
    private $model;
    
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function create(array $payload = []) {
        return $this->model->create($payload);
    }

    public function findById(
        int $modelId,
        array $column = ['*'],
        array $relation = [],
    ){
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }

    
    public function findByField(string $field = '', mixed $value = null) {
        return $this->model->where($field, $value)->first();
    }
}