<?php 

namespace App\Services\Interfaces;
use App\Services\Interfaces\BaseServiceInterface;

interface PermissionServiceInterface extends BaseServiceInterface {
    public function createModulePermission($request);
}