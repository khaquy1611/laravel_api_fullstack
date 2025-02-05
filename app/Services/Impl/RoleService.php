<?php

namespace App\Services\Impl;
use App\Services\Impl\BaseService;
use App\Repositories\RoleRepository;
use App\Services\Interfaces\RoleServiceInterface;
use Illuminate\Support\Str;

class RoleService extends BaseService implements RoleServiceInterface
{
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct($roleRepository);
    }   

    protected function getSearchField() : array {
        return ['name'];
    }
    protected function getPerPage() : int {
        return 20;
    }
    protected function getSimpleFilter() : array {
        return ['publish'];
    }
    protected function getComplexFilter() : array {
        return ['id'];
    }
    protected function getDateFilter() : array {
        return ['created_at'];
    }
    protected function requestOnlyPayload(): array
    {
        return ['name', 'publish', 'permisions'];
    }
    protected function processPayload() {
        return $this->generateSlug()->generateSomething();
    }
    protected function generateSlug() {
        $this->payload['slug'] = Str::slug($this->payload['name']);
        return $this;
    }

    protected function generateSomething() {
        return $this;
    }

    protected function getManyToManyRelationShip() : array {
        return ['permisions'];
    }

}