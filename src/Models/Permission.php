<?php

namespace Karacraft\RolesAndPermissions\Models;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Karacraft\RolesAndPermissions\Models\Base;
use Karacraft\RolesAndPermissions\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Base
{
    use HasFactory;

    protected $table = 'permissions';
    protected $fillable = ['title','slug','model','method'];
    
    /** Relationships */
    public function roles(){return $this->belongsToMany(Role::class, 'roles_permissions');}
    public function users(){return $this->belongsToMany(User::class ,'users_permissions');}
}
