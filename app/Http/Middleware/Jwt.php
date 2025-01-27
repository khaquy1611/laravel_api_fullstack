<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Http\Resources\ApiResource;

class Jwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!$request->hasHeader('Authorization')) {
                return ApiResource::message('Authorization header không được cung cấp', Response::HTTP_BAD_REQUEST);
            }
            $user = JwtAuth::parseToken()->authenticate();
            if (!$user) {
                return ApiResource::message('Không tìm thấy người dùng', Response::HTTP_NOT_FOUND);
            }
        }catch(TokenExpiredException $e) {
            return ApiResource::message('Token đã hết hạn', Response::HTTP_UNAUTHORIZED);
        }catch(TokenInvalidException $e) {
            return ApiResource::message('Token không hợp lệ', Response::HTTP_UNAUTHORIZED);
        }catch (JWTException $e) {
            return ApiResource::message('Token không tìm thấy', Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}