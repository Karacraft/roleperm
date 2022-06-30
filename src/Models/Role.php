<?php

namespace Karacraft\RolesAndPermissions\Models;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Karacraft\RolesAndPermissions\Models\Base;
use Karacraft\RolesAndPermissions\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Karacraft\RolesAndPermissions\Traits\RolesAndPermissionsTrait;

class Role extends Base
{
    use HasFactory, RolesAndPermissionsTrait;

    protected $table = 'roles';
    protected $fillable = ['title','description','slug'];
 
    /** Relationships */
    public function permissions(){return $this->belongsToMany(Permission::class,'roles_permissions');}
    public function users(){return $this->belongsToMany(User::class, 'users_roles');}
}
