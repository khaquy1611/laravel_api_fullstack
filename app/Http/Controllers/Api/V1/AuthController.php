<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Resources\ApiResource;
use App\Services\Impl\RefreshTokenService;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    private $refreshTokenService;
    private $refreshTokenRepository;
    private $userRepository;
    public function __construct(
        RefreshTokenService $refreshTokenService, 
        RefreshTokenRepository $refreshTokenRepository, 
        UserRepository $userRepository)
    {
        $this->refreshTokenService = $refreshTokenService;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->userRepository = $userRepository;
    }
    
    public function authenticate(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->string('email'),
            'password' => $request->string('password')
        ];
        auth('api')->setTTL(2);
        if (! $token = auth('api')->attempt($credentials)) {    
            return ApiResource::message('Đăng nhập thất bại', Response::HTTP_UNAUTHORIZED);
        }
        // Tạo bảng refresh token
        // Tạo ra refresh token
        $refreshTokenPayload = [
            'refresh_token' => Str::uuid(),
            'user_id' => auth('api')->user()->id,
            'expires_at' => now()->addDays()
        ];
        if ($this->refreshTokenService->create($refreshTokenPayload)) {
            return ApiResource::success($this->respondWithToken($token, $refreshTokenPayload), 'Tạo RefreshToken thành công' , Response::HTTP_OK);
        }
        return ApiResource::message('Đăng nhập thất bại', Response::HTTP_UNAUTHORIZED);
    }


    protected function respondWithToken($token = '', $refreshTokenPayload = '')
    {
        return [
            'access_token' => $token,
            'refresh_token' => $refreshTokenPayload['refresh_token'],
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    public function me() {
        $auth = auth('api')->user();
        return ApiResource::success(['user' => $auth], 'Lấy thông tin người dùng thành công', Response::HTTP_OK);
    }

    public function refresh(RefreshTokenRequest $request) {
        $refreshToken = $this->refreshTokenRepository->findRefreshTokenValid($request->input('refresh_token'));
        $user = $this->userRepository->findById($refreshToken->user_id);
        if (!$user) {
            return ApiResource::message('Không tìm thấy người dùng', Response::HTTP_NOT_FOUND);
        }
        // token truy cập cũ không còn hiệu lực
        try {
            auth('api')->invalidate(true);
        }catch(TokenExpiredException $e) {
            
        }catch(TokenInvalidException $e) {
            return responApiResource::message('Token không hợp lệ', Response::HTTP_UNAUTHORIZED);
        }catch (JWTException $e) {
            return ApiResource::message('Token không tìm thấy', Response::HTTP_UNAUTHORIZED);
        }
        
        // tạp ra access token mới
        auth('api')->setTTL(60*24);
        $token = auth('api')->login($user);
        if ($token) {
            return ApiResource::success($this->respondWithToken($token, $refreshToken['refresh_token']), 'Lấy token mới thành công', Response::HTTP_OK);
        }
        return ApiResource::message('NetWork Error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function logout() {
        try {
            $user = auth('api')->user();
            $this->refreshTokenRepository->deleteTokenByUserId($user->id);
            auth('api')->invalidate(true);
            auth('api')->logout();
            return ApiResource::message('Đăng xuất thành công', Response::HTTP_OK);
        
        }catch (Exception $e) {
            return ApiResource::message('Đăng xuất thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}