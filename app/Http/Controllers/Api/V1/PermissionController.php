<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Resources\ApiResource;
use App\Http\Resources\PermissionResource;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use App\Http\Requests\Permission\DeleteRequest;
use App\Http\Requests\Permission\DeleteMultipleRequest;
use App\Http\Requests\Permission\CreatModulePermissionRequest;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/** 
 * @OA\Tag(
 *    name="USER",
 *    description="USER API endpoints"
 * )
 * @OA\Server(
 *   url="http://localhost:8000/api/v1/auth/users",
 *   description="Local users api Server"
 * )
 * @OA\Schema(  
 *  schema="USER",
 * type="object",
 * title="USER",
 * properties={
 *   @OA\Property(property="id", type="integer", description="The unique identifier of the user."),
 *   @OA\Property(property="name", type="string", description="The name of the user."),
 *   @OA\Property(property="email", type="string", description="The email of the user."),
 *   @OA\Property(property="password", type="string", description="The password of the user."),
 *   @OA\Property(property="publish", type="int", description="The publish status of the user."),
 *   @OA\Property(property="created_at", type="string", format="date-time", description="The date/time the user was created."),
 *   @OA\Property(property="updated_at", type="string", format="date-time", description="The date/time the user was updated."),
 * }
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header",
 *     name="Authorization"
 * )
 */


class PermissionController extends BaseController
{
    protected $permissionService;
    protected $resource = \App\Http\Resources\PermissionResource::class;
    public function __construct(
        PermissionService $permissionService,
    )
    {
        parent::__construct($permissionService);
        $this->permissionService = $permissionService;
    }
    /**
     * @OA\Get(
     *    path="/api/v1/auth/users/all",
     *  operationId="getAllUsers",
     *   summary="Get All Users Record(s)",
     *   security={{"bearerAuth":{}}},
     *  tags={"USER"},
     * @OA\Response(
     *   response=200,
     *  description="List of users retrieved successfully",
     * @OA\JsonContent(
     *  type="array",
     * @OA\Items(ref="#/components/schemas/USER")
     * ),
     * @OA\Response(
     *  response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to fetch users")
     * )
     * )
     * )
     * )
     */
    public function all(Request $request) {
        return parent::all($request);
    }

    /** 
     * @OA\Post(
     *   path="/api/v1/auth/users",
     *  operationId="createUser",
     *  summary="Create a new USER",
     * security={{"bearerAuth":{}}},
     * tags={"USER"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Admin", description="User name"),
     * @OA\Property(property="email", type="string", example="khaquy12a2@gmail.com", description="User email"), 
     * @OA\Property(property="password", type="string", example="123456", description="User password"), 
     * @OA\Property(property="publish", type="int", example=1, description="User publish status"))
     * ),
     * @OA\Response(
     * response=200,
     * description="User created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", ref="#/components/schemas/USER"),
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="USER created successfully"),
     * @OA\Property(property="code", type="integer", example=200)
     * )
     * ),
     * @OA\Response(
     *  response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to create user")
     * )
     * )
     * )
     * 
     */
    public function store(Request $request) {
        return parent::store($request);
    }
    /**
     * @OA\Put(
     *  path="/api/v1/auth/users/{id}",
     * operationId="updateUser",
     * summary="Update an existing User",
     * security={{"bearerAuth":{}}},
     * tags={"USER"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="USER ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Admin", description="User name"), 
     * @OA\Property(property="email", type="string", example="khaquy12a2@gmail.com", description="User name"), 
     * @OA\Property(property="password", type="string", example="123456", description="User password"), 
     * @OA\Property(property="publish", type="int", example=1, description="User publish status"))
     * ),
     * @OA\Response(
     * response=200,
     * description="User updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", ref="#/components/schemas/USER"),
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="USER updated successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to update user"),
     * )
     * )
     * ) 
     */
    public function update(Request $request, mixed $id = null) {
        return parent::update($request, $id);
    }
    /**
     * @OA\Get(
     * path="/api/v1/auth/users/{id}",
     * operationId="getUserById",
     * summary="Get USER by ID",
     * security={{"bearerAuth":{}}},
     * tags={"USER"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="USER ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="User retrieved successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", ref="#/components/schemas/USER"),
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="User retrieved successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to fetch users"),
     * )
     * )
     * )
     */
    public function show($id = null) {
        return parent::show($id);
    }
    /** 
     * @OA\Delete(
     * path="/api/v1/auth/users/{id}",
     * operationId="deleteUser",
     * summary="Delete an existing USER",
     * security={{"bearerAuth":{}}},
     * tags={"USER"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="USER ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="User deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="User deleted successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to delete users"),
     * )
     * )
     * )
     */
    public function destroy(mixed $id = null) {
        return parent::destroy($id);
    }
    /**
     * @OA\Delete(
     * path="/api/v1/auth/users/delete-multiple",
     * operationId="deleteMultipleusers",
     * summary="Delete multiple users",
     * security={{"bearerAuth":{}}},
     * tags={"USER"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="ids", type="array", @OA\Items(type="integer", example={1,2}, description="Array of Us IDs (must be integers)")),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="users deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="users deleted successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to delete users"),
     * )
     * )
     * )
     */
    public function deleteMultiple(Request $request) {
        return parent::deleteMultiple($request);
    }

    protected function getStoreRequest(): string
    {
        return StoreRequest::class;
    }

    protected function getUpdateRequest(): string
    {
        return UpdateRequest::class;
    }

    protected function getDeleteRequest(): string
    {
        return DeleteRequest::class;
    }

    protected function getDeleteMultipleRequest(): string
    {
        return DeleteMultipleRequest::class;
    }
    
    public function createModulePermission(CreatModulePermissionRequest $request) {
        $result = $this->permissionService->createModulePermission($request);
        if ($result['flag']) {
            $permission = PermissionResource::collection($result['permissions']);
            return ApiResource::success($permission, 'Tạo mới dữ liệu thành công', Response::HTTP_OK);
            }
        return ApiResource::error($result['error'], 'Tạo mới dữ liệu thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}