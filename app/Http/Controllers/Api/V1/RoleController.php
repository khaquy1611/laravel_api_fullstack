<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\RoleResource;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Requests\Role\DeleteRequest;
use App\Http\Requests\Role\DeleteMultipleRequest;
use App\Services\Interfaces\RoleServiceInterface as RoleService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/** 
 * @OA\Tag(
 *    name="Role",
 *    description="Role API endpoints"
 * )
 * @OA\Server(
 *   url="http://localhost:8000/api/v1/auth/roles",
 *   description="Local role api Server"
 * )
 * @OA\Schema(  
 *  schema="Role",
 * type="object",
 * title="Role",
 * properties={
 *   @OA\Property(property="id", type="integer", description="The unique identifier of the role."),
 *   @OA\Property(property="name", type="string", description="The name of the role."),
 *   @OA\Property(property="slug", type="string", description="The slug of the role."),
 *   @OA\Property(property="publish", type="int", description="The publish status of the role."),
 *   @OA\Property(property="created_at", type="string", format="date-time", description="The date/time the role was created."),
 *   @OA\Property(property="updated_at", type="string", format="date-time", description="The date/time the role was updated."),
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
 * 
 */


class RoleController extends BaseController
{
    protected $roleService;
    protected $resource = \App\Http\Resources\RoleResource::class;
    public function __construct(
        RoleService $roleService,
    )
    {
        parent::__construct($roleService);
    }
    /**
     * @OA\Get(
     *    path="/api/v1/auth/roles/all",
     *  operationId="getAllRoles",
     *   summary="Get All Roles Record(s)",
     *   security={{"bearerAuth":{}}},
     *  tags={"Role"},
     * @OA\Response(
     *   response=200,
     *  description="List of roles retrieved successfully",
     * @OA\JsonContent(
     *  type="array",
     * @OA\Items(ref="#/components/schemas/Role")
     * ),
     * @OA\Response(
     *  response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to fetch roles")
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
     *   path="/api/v1/auth/roles",
     *  operationId="createRole",
     *  summary="Create a new Role",
     * security={{"bearerAuth":{}}},
     * tags={"Role"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(@OA\Property(property="name", type="string", example="Admin", description="Role name"), @OA\Property(property="publish", type="int", example=1, description="Role publish status"))
     * ),
     * @OA\Response(
     * response=200,
     * description="Role created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", ref="#/components/schemas/Role"),
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="Role created successfully"),
     * @OA\Property(property="code", type="integer", example=200)
     * )
     * ),
     * @OA\Response(
     *  response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to create role")
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
     *  path="/api/v1/auth/roles/{id}",
     * operationId="updateRole",
     * summary="Update an existing Role",
     * security={{"bearerAuth":{}}},
     * tags={"Role"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="Role ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(@OA\Property(property="name", type="string", example="Admin", description="Role name"), @OA\Property(property="publish", type="int", example=1, description="Role publish status"))
     * ),
     * @OA\Response(
     * response=200,
     * description="Role updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", ref="#/components/schemas/Role"),
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="Role updated successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to update role"),
     * )
     * )
     * ) 
     */
    public function update(Request $request, mixed $id = null) {
        return parent::update($request, $id);
    }
    /**
     * @OA\Get(
     * path="/api/v1/auth/roles/{id}",
     * operationId="getRoleById",
     * summary="Get Role by ID",
     * security={{"bearerAuth":{}}},
     * tags={"Role"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="Role ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Role retrieved successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", ref="#/components/schemas/Role"),
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="Role retrieved successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to fetch role"),
     * )
     * )
     * )
     */
    public function show($id = null) {
        return parent::show($id);
    }
    /** 
     * @OA\Delete(
     * path="/api/v1/auth/roles/{id}",
     * operationId="deleteRole",
     * summary="Delete an existing Role",
     * security={{"bearerAuth":{}}},
     * tags={"Role"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="Role ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Role deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="Role deleted successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to delete role"),
     * )
     * )
     * )
     */
    public function destroy(mixed $id = null) {
        return parent::destroy($id);
    }
    /**
     * @OA\Delete(
     * path="/api/v1/auth/roles/delete-multiple",
     * operationId="deleteMultipleRoles",
     * summary="Delete multiple Roles",
     * security={{"bearerAuth":{}}},
     * tags={"Role"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="ids", type="array", @OA\Items(type="integer", example={1,2}, description="Array of Role IDs (must be integers)")),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Roles deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="boolean", example="true"),
     * @OA\Property(property="message", type="string", example="Roles deleted successfully"),
     * @OA\Property(property="code", type="integer", example=200),
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internet Server Error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Error: Unable to delete roles"),
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
    
}