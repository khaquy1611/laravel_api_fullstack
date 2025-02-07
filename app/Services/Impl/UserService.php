<?php

namespace App\Services\Impl;
use App\Services\Impl\BaseService;
use App\Repositories\UserRepository;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\Impl\ImageUploadService;

class UserService extends BaseService implements UserServiceInterface
{
    protected $userRepository;
    protected $payload;
    protected $imageUploadService;
    
    public function __construct(
        UserRepository $userRepository,
        ImageUploadService $imageUploadService
    )
    {
        parent::__construct($userRepository);
        $this->imageUploadService = $imageUploadService;
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
        return ['name', 'email', 'password', 'slug', 'birthday', 'publish', 'roles'];
    }
    protected function processPayload($request) {
        return $this->generateSlug()->uploadAvatar($request);
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
    protected function uploadAvatar($request) {
        $arguments = [
            'files' => $request->file('image'),
            'folder' => 'avatar',
            'pipelineKey' => 'default',
            'overrideOptions' => [
                'optimize' => [
                    'quality' => 80
                ],
            ]
        ];
        $processImage = $this->imageUploadService->upload(...$arguments);
        $this->payload['avatar'] = $processImage['files']['path'];
        return $this;
    }
    protected function getManyToManyRelationShip() : array {
        return ['roles'];
    }
}