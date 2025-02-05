<?php

namespace App\Services\Impl;
use App\Services\Impl\BaseService;
use App\Repositories\UserRepository;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserService extends BaseService implements UserServiceInterface
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
    }   

    protected function getSearchField() : array {
        return ['name', 'email'];
    }
    protected function getPerPage() : int {
        return 20;
    }
    protected function getSimpleFilter() : array {
        return ['publish'];
    }
    protected function getComplexFilter() : array {
        return ['id', 'age'];
    }
    protected function getDateFilter() : array {
        return ['created_at', 'birthday'];
    }
    protected function requestOnlyPayload(): array
    {
        return ['name', 'email', 'password', 'slug', 'birthday', 'publish'];
    }
    protected function processPayload() {
        return $this->generateSlug()->generateSomething();
    }
    protected function generateSlug() {
        $this->payload['slug'] = Str::slug($this->payload['name']);
        return $this;
    }
    
    protected function caculateAgeFromBirthDay() {
        $this->payload['age'] = Carbon::parse($this->payload['birthday'])->age;
        return $this;
    }

    protected function generateSomething() {
        return $this->caculateAgeFromBirthDay();
    }
}