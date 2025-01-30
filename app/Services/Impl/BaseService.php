<?php

namespace App\Services\Impl;
use Illuminate\Support\Facades\DB;

abstract class BaseService
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
    private function simpleFilter($request, array $filters = []) {
        $simplefilter = [];
        if (count($filters)) {
            foreach($filters as $filter) {
                if ($request->has($filter)) {
                    $simplefilter[$filter] = $request->input($filter);
                }
            }
        }
        return $simplefilter;
    }
    private function complexFilter($request, array $complexfilters = []) {
        $conditions = [];
        foreach($complexfilters as $filter) {
            if ($request->has($filter)) {
                $conditions[$filter] = $request->input($filter);
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
            'simpleFilter' => $this->simpleFilter($request, $this->getSimpleFilter()),
            'complexFilter' => $this->complexFilter($request, $this->getComplexFilter()),
            'dateFilter' => $this->complexFilter($request, $this->getDateFilter()),
            ];
    }

    protected function setPayload($request) {
        $this->payload = $request->only($this->requestOnlyPayload());
        return $this;
    }

    protected function buildPayload() {
        return $this->payload;
    }
    protected function processPayload() {
        return $this;
    }
    public function paginate($request) {
        $specifications = $this->specifications($request);
        return $this->repository->paginate($specifications);
    }
    public function getList() {
        return $this->repository->all();
    }
    public function save($request, mixed $id = null) {
        DB::beginTransaction();
        try {
            $payload = $this->setPayload($request)->processPayload()->buildPayload();
            $result = $this->repository->save($payload, $id);
            DB::commit();
            return [
                'data' => $result,
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

    public function delete(mixed $id = null) {
        DB::beginTransaction();
        try {
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
}