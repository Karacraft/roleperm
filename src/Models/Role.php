<?php

namespace Karacraft\Roleperm\Models;

use App\Models\User;
use App\Traits\RolePermission;
use Illuminate\Support\Facades\DB;
use Karacraft\Roleperm\Models\Base;
use Karacraft\Roleperm\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Base
{
    use HasFactory, RolePermission;

    protected $table = 'roles';
    protected $fillable = ['title','description','slug'];
    public $timestamps = false; // Not Using Timestamps
    /** Methods */
    public static function createBaseRoles()
    {
        DB::beginTransaction();
        try {

            Role::create(['title' => 'Super Admin', 'description' => 'Full Authorized Administrative Role' , 'slug' => 'super_admin']);
            Role::create(['title' => 'Store Manager', 'description' => 'All Inventory Authorizations' , 'slug' => 'store_manager']);
            Role::create(['title' => 'Line Manager', 'description' => 'HOD - Super Department User' , 'slug' => 'line_manager']);
            Role::create(['title' => 'User', 'description' => 'Normal Department User' , 'slug' => 'user']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /** Relationships */
    public function permissions(){return $this->belongsToMany(Permission::class,'roles_permissions');}
    //  Role can have many users
    // public function users(){return $this->belongsToMany(config('auth.providers.users.model'), 'users_roles');}
    public function users(){return $this->belongsToMany(User::class, 'users_roles');}
}
