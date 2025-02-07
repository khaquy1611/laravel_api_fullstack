<?php

namespace App\Services\Impl;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;

abstract class BaseService implements BaseServiceInterface
{
    protected $repository;
    protected $payload;

    abstract protected function requestOnlyPayload(): array;
    abstract protected function getSearchField(): array;
    abstract protected function getPerPage(): int;
    abstract protected function getSimpleFilter(): array;
    abstract protected function getComplexFilter(): array;
    abstract protected function getDateFilter(): array;

    public function __construct(mixed $repository = null) {
        $this->repository = $repository;
    }

    private function buildFilters($request, array $filters = []) {
        $conditions = [];
        if (count($filters)) {
            foreach($filters as $filter) {
                if ($request->has($filter)) {
                    $conditions[$filter] = $request->input($filter);
                }
            }
        }
        return $conditions;
    }
    
    private function specifications($request) {
        return [
            'keyword' => [
                'q' => $request->input('keyword'),
                'field' => $this->getSearchField()
            ],
            'perpage' => ($request->input('perpage')) ? ($request->input('perpage')) : $this->getPerPage(),
            'sortBy' => ($request->input('sortBy')) ? explode(',' ,$request->input('sortBy')) : ['id', 'desc'],
            'filters' => [
                'simple' => $this->buildFilters($request, $this->getSimpleFilter()),
                'complex' => $this->buildFilters($request, $this->getComplexFilter()),
                'date' => $this->buildFilters($request, $this->getDateFilter())
            ],
            'scope' => [
                'view' => $request->input('viewScope'),
                'action' => $request->input('actionScope')
            ]
            ];
    }

    public function show($id) {
        return $this->repository->findById($id);
    }
    
    protected function setPayload($request) {
        $this->payload = $request->only($this->requestOnlyPayload());
        return $this;
    }

    protected function buildPayload() {
        return $this->payload;
    }
    protected function processPayload($request) {
        return $this;
    }
    public function paginate($request, $recordType = 'paginate') {
        $specifications = $this->specifications($request);
        return $this->repository->paginate($specifications, $recordType);
    }

    public function save($request, mixed $id = null, $method = 'create') {
        DB::beginTransaction();
        try {
            if ($method == 'update') {
                $this->validatePermission($request, $id);
            }
            $payload = $this->setPayload($request)
                            ->processPayload($request)
                            ->buildPayload();
            $model = $this->repository->save($payload, $id);
            $this->handleManyToManyRealtion($model, $payload);
            DB::commit();
            return [
                'data' => $model,
                'flag' => true
            ];
        }
        catch(AuthorizationException $e) {
            DB::rollBack();
            throw $e;
        } 
        catch(Exeption $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }     
    }

    public function delete(mixed $id = null) {
        DB::beginTransaction();
        try {
            $this->validatePermission($request, $id);
            $this->repository->delete($id);
            DB::commit();
            return [
                'flag' => true
            ];
        }catch(Exeption $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }
    }

    public function deleteMultiple($request) {
        DB::beginTransaction();
        try {
            $ids = $request->input('ids');
            if (count($ids)) {
                $deletedCount = $this->repository->deleteWhereIn($ids);
                DB::commit();
                return [
                'deletedCount' => $deletedCount,
                'flag' => true
            ];
            }
        }catch(Exeption $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }  
    }
    
    private function handleManyToManyRealtion($model = null, $payload = []) : void {
        $relations = $this->getManyToManyRelationShip();
        if (count($relations)) {
            foreach($relations as $relation) {
                if (isset($payload[$relation])) {
                    $relationData = $payload[$relation];
                    if (is_string($relationData)) {
                        $relationData = json_decode($relationData);
                    }
                    $model->{$relation}()->sync($relationData);
                }
            }
        }
    }
    
    private function validatePermission($request, $id) {
        $action = $request->input('actionScope') === 'all';
        if (!$action) {
            $model = $this->repository->findById($id);
            if (isset($model->user_id) && $model->user_id !== auth('api')->user()->id) {
                throw new AuthorizationException('Permission Denined');
            } 
        }
    }
    protected function getManyToManyRelationShip() : array {
        return [];
    }
}