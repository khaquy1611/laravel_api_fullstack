<?php

namespace App\Services\Impl;
use Illuminate\Support\Facades\DB;
use App\Repositories\RefreshTokenRepository;
use Exception;

class RefreshTokenService
{
    private $refreshTokenRepository;
    public function __construct(RefreshTokenRepository $refreshTokenRepository)
    {
        $this->refreshTokenRepository = $refreshTokenRepository;
    }
    public function create($payload) {
        DB::beginTransaction();
        try {
            $refreshToken = $this->refreshTokenRepository->create($payload);
            DB::commit();
            return true;
        }catch(Exeption $e) {
            DB::rollBack();
            return false;
        }     
    }
}