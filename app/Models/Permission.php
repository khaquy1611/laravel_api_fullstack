<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Query; 

class Permission extends Model
{
    use Query;
    protected $table = 'permisions';

    protected $fillable = [
        'name',
        'publish',
    ];

    public function roles() : BelongsToMany {
        return $this->belongsToMany(Role::class, 'role_permission')->withTimestamps();
    }
}