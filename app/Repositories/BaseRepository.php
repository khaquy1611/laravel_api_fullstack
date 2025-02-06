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

    public function deleteWhereIn(array $ids = []) {
        return $this->model->whereIn('id', $ids)->delete();
    }
    
    public function findById(
        mixed $modelId = null,
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

    public function all() {
        return $this->model->all();
    }
    public function paginate(array $specs = [], string $recordType = 'paginate') {
        return $this->model
        ->keyword($specs['keyword'] ?? [])
        ->simpleFilter($specs['filters']['simple'] ?? [])
        ->complexFilter($specs['filters']['complex'] ?? [])
        ->dateFilter($specs['filters']['date'] ?? [])  
        ->permissionFilter($specs['scope'] ?? [])
        ->orderBy($specs['sortBy'][0], $specs['sortBy'][1])
        ->when(
            $recordType == 'paginate' , 
            fn($q) => $q->paginate($specs['perpage']),
            fn($q) => $q->get(),
        );
    }

    public function checkExists(string $field = '' , mixed $value = null) {
        return $this->model->where( $field, $value)->exists();
    }
}