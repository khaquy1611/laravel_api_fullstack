<?php 

namespace App\Services\Interfaces;

interface BaseServiceInterface {
    public function paginate($request, $recordsType = '');
    public function save($request, mixed $id = null);
    public function delete(mixed $id = null);
    public function deleteMultiple($request);
    public function show($id);
}