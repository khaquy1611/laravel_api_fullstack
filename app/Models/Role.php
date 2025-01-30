<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
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
}