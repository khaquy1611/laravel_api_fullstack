<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Query; 

class Role extends Model
{
    use Query;
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'publish',
    ];

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'role_user')->withTimestamps();
    }

    public function permisions() : BelongsToMany {
        return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }
}