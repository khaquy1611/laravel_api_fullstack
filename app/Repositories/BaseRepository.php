<?php

namespace App\Repositories;
class BaseRepository
{
    private $model;
    
    public function __construct(mixed $model = null)
    {
        $this->model = $model;
    }

    public function create(array $payload = []) {
        return $this->model->create($payload)->fresh();
    }

    public function update(array $payload = [], int $modelId = 0) {
        $model = $this->findById($modelId);
        $model->fill($payload);
        $model->save();
        return $model;
    }

    public function delete(int $modelId = 0) {
        return $this->findById($modelId)->delete();
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

    public function save(array $payload = [], mixed $id = null) {
        return ($id) ? $this->update($payload, $id) : $this->create($payload);
    }
}