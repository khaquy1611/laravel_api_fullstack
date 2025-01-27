<?php

namespace App\Services\Impl;
use App\Services\Impl\BaseService;
use App\Repositories\RoleRepository;
use Illuminate\Support\Str;

class RoleService extends BaseService
{
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct($roleRepository);
    }   

    protected function requestOnlyPayload(): array
    {
        return ['name', 'publish'];
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
}