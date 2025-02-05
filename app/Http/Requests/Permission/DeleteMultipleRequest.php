<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;
use App\Repositories\PermissionRepository;

class DeleteMultipleRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $userRepository;
    public function __construct() {
        $this->userRepository = app(PermissionRepository::class);
    }

    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */ 
    public function rules(): array
    {
        return [
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $ids = $this->input('ids');
    
            // Kiểm tra xem ids có phải là mảng không
            if (!is_array($ids)) {
                $validator->errors()->add('ids', 'ids phải là mảng.');
                return;
            }
    
            // Kiểm tra và chuyển đổi tất cả các phần tử trong mảng thành số nguyên
            foreach ($ids as $id) {
                if (!is_numeric($id) || intval($id) != $id) {
                    $validator->errors()->add('ids', "ID không hợp lệ: {$id}. dữ liệu trong mảng ids phải là số nguyên.");
                    return;
                }
            }
    
            // Chuyển đổi tất cả các ID thành số nguyên
            $this->merge(['ids' => array_map('intval', $ids)]);
        });
        
    }
}