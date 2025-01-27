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
                $resource = ApiResource::message('Authorization header không được cung cấp', Response::HTTP_BAD_REQUEST);
                return response()->json($resource, Response::HTTP_NOT_FOUND);
            }
            $user = JwtAuth::parseToken()->authenticate();
            if (!$user) {
                $resource = ApiResource::message('Không tìm thấy người dùng', Response::HTTP_NOT_FOUND);
                return response()->json($resource, Response::HTTP_NOT_FOUND);
            }
        }catch(TokenExpiredException $e) {
            $resource = ApiResource::message('Token đã hết hạn', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }catch(TokenInvalidException $e) {
            $resource = ApiResource::message('Token không hợp lệ', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }catch (JWTException $e) {
            $resource = ApiResource::message('Token không tìm thấy', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}