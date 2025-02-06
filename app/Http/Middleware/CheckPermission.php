<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\ApiResource;

class CheckPermission
{
    private $auth;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __construct() {
        /** 
         * @var \Tymon\JWTAuth\JWTGuard
         */
        $this->auth = auth('api'); 
    }
    
    public function handle(Request $request, Closure $next): Response
    {
        $controller = class_basename(explode('@', $request->route()->getActionName())[0]);
        $model = strtolower(str_replace('Controller', '', $controller));
        $method = $request->route()->getActionMethod();
         /** 
         * @var User users
         */
        $user = $this->auth->user();
       
        if (!$user) {
            // If no user is authenticated, return an unauthorized response
            return response()->json(['error' => 'Không tìm thấy người dùng'], 401);
        }
        $permissions = $user->getJWTCustomClaims()['permisions'];
      
        $permissionName = "{$model}:{$method}";
       
        if (!in_array($permissionName, $permissions)) {
            return ApiResource::message('Permission denined', Response::HTTP_FORBIDDEN);
        }

        $mergeRequest = [
            'viewScope' => in_array("{$method}:viewAll", $permissions) ? 'all' : 'own',
            'actionScope' => in_array("{$method}:actionAll", $permissions) ? 'all' : 'own'
        ];
        $request->merge($mergeRequest);
        return $next($request);
    }
}