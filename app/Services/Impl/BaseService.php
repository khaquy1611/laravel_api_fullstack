<?php

namespace App\Services\Impl;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected $repository;
    protected $payload;
    
    abstract protected function requestOnlyPayload(): array;

    public function __construct(mixed $repository = null) {
        $this->repository = $repository;
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