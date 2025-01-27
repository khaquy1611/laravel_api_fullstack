<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use App\Models\RefreshToken;

class RefreshTokenRepository extends BaseRepository
{
    private $model;
    public function __construct(RefreshToken $model)
    {
       parent::__construct($model);
       $this->model = $model;
    }

    public function findRefreshTokenValid(string $refreshToken = '') {
        return $this->model->where('refresh_token', $refreshToken)->whereDate('expires_at', '>=', now())->first();
    }
}