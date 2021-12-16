<?php

namespace Karacraft\Roleperm\Models;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Karacraft\Roleperm\Models\Base;
use Karacraft\Roleperm\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Base
{
    use HasFactory;

    protected $table = 'permissions';
    protected $fillable = ['title','slug','model','method'];
    public $timestamps = false; // Not Using Timestamps


    public static function userPermissions()
    {
        $matchModels = ['Demand','GatePass','Mrfd'];
        $matchMethods = ['create','edit','update','view','list'];
        $userPermissions = Permission::whereIn('model',$matchModels)->whereIn('method',$matchMethods)->get();
        return $userPermissions;
    }

    /** Methods */
    public static function createBasePermissions()
    {
        DB::beginTransaction();
        try {

            //  User Model
            Permission::create(['title' => 'Create User', 'slug' => 'create_user' , 'method' => 'create', 'model' => 'User' ]);
            Permission::create(['title' => 'Edit User', 'slug' => 'edit_user' , 'method' => 'edit', 'model' => 'User' ]);
            Permission::create(['title' => 'Update User', 'slug' => 'update_user' , 'method' => 'update', 'model' => 'User' ]);
            Permission::create(['title' => 'Show User', 'slug' => 'view_user' , 'method' => 'view', 'model' => 'User' ]);
            Permission::create(['title' => 'Delete User', 'slug' => 'delete_user' , 'method' => 'delete', 'model' => 'User' ]);
            Permission::create(['title' => 'Show User List', 'slug' => 'view_users' , 'method' => 'list', 'model' => 'User' ]);
            Permission::create(['title' => 'View User Tile', 'slug' => 'view_user_tile' , 'method' => 'tile', 'model' => 'User' ]);
            Permission::create(['title' => 'Change User Password', 'slug' => 'change_user_password' , 'method' => 'Change Password', 'model' => 'User' ]);
            //  Permission Model
            Permission::create(['title' => 'Create Permission', 'slug' => 'create_permission' , 'method' => 'create', 'model' => 'Permission' ]);
            Permission::create(['title' => 'Edit Permission', 'slug' => 'edit_permission' , 'method' => 'edit', 'model' => 'Permission' ]);
            Permission::create(['title' => 'Update Permission', 'slug' => 'update_permission' , 'method' => 'update', 'model' => 'Permission' ]);
            Permission::create(['title' => 'Show Permission', 'slug' => 'view_permission' , 'method' => 'view', 'model' => 'Permission' ]);
            Permission::create(['title' => 'Delete Permission', 'slug' => 'delete_permission' , 'method' => 'delete', 'model' => 'Permission' ]);
            Permission::create(['title' => 'Show Permission List', 'slug' => 'view_permissions' , 'method' => 'list', 'model' => 'Permission' ]);
            //  Role Model
            Permission::create(['title' => 'Create Role', 'slug' => 'create_role' , 'method' => 'create', 'model' => 'Role' ]);
            Permission::create(['title' => 'Edit Role', 'slug' => 'edit_role' , 'method' => 'edit', 'model' => 'Role' ]);
            Permission::create(['title' => 'Update Role', 'slug' => 'update_role' , 'method' => 'update', 'model' => 'Role' ]);
            Permission::create(['title' => 'Show Role', 'slug' => 'view_role' , 'method' => 'view', 'model' => 'Role' ]);
            Permission::create(['title' => 'Delete Role', 'slug' => 'delete_role' , 'method' => 'delete', 'model' => 'Role' ]);
            Permission::create(['title' => 'Show Role List', 'slug' => 'view_roles' , 'method' => 'list', 'model' => 'Role' ]);
            // Plant
            Permission::create(['title' => 'Create Plant', 'slug' => 'create_plant' , 'method' => 'create', 'model' => 'Plant' ]);
            Permission::create(['title' => 'Edit Plant', 'slug' => 'edit_plant' , 'method' => 'edit', 'model' => 'Plant' ]);
            Permission::create(['title' => 'Update Plant', 'slug' => 'update_plant' , 'method' => 'update', 'model' => 'Plant' ]);
            Permission::create(['title' => 'Show Plant', 'slug' => 'view_plant' , 'method' => 'view', 'model' => 'Plant' ]);
            Permission::create(['title' => 'Delete Plant', 'slug' => 'delete_plant' , 'method' => 'delete', 'model' => 'Plant' ]);
            Permission::create(['title' => 'Show Plant List', 'slug' => 'view_plants' , 'method' => 'list', 'model' => 'Plant' ]);
            Permission::create(['title' => 'View Plant Tile', 'slug' => 'view_plant_tile' , 'method' => 'tile', 'model' => 'Plant' ]);
            //  Department Model
            Permission::create(['title' => 'Create Department', 'slug' => 'create_department' , 'method' => 'create', 'model' => 'Department' ]);
            Permission::create(['title' => 'Edit Department', 'slug' => 'edit_department' , 'method' => 'edit', 'model' => 'Department' ]);
            Permission::create(['title' => 'Update Department', 'slug' => 'update_department' , 'method' => 'update', 'model' => 'Department' ]);
            Permission::create(['title' => 'Show Department', 'slug' => 'view_department' , 'method' => 'view', 'model' => 'Department' ]);
            Permission::create(['title' => 'Delete Department', 'slug' => 'delete_department' , 'method' => 'delete', 'model' => 'Department' ]);
            Permission::create(['title' => 'Show Department List', 'slug' => 'view_departments' , 'method' => 'list', 'model' => 'Department' ]);
            Permission::create(['title' => 'View Department Tile', 'slug' => 'view_department_tile' , 'method' => 'tile', 'model' => 'Department' ]);
            //  GatePass
            Permission::create(['title' => 'Create GatePass', 'slug' => 'create_gatepass' , 'method' => 'create', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Edit GatePass', 'slug' => 'edit_gatepass' , 'method' => 'edit', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Update GatePass', 'slug' => 'update_gatepass' , 'method' => 'update', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Show GatePass', 'slug' => 'view_gatepass' , 'method' => 'view', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Delete GatePass', 'slug' => 'delete_gatepass' , 'method' => 'delete', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Show GatePass List', 'slug' => 'view_gatepasses' , 'method' => 'list', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Approve GatePass', 'slug' => 'approve_gatepass' , 'method' => 'approve', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Reject GatePass', 'slug' => 'reject_gatepass' , 'method' => 'reject', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Cancle GatePass', 'slug' => 'cancel_gatepass' , 'method' => 'cancel', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'Hold GatePass', 'slug' => 'hold_gatepass' , 'method' => 'hold', 'model' => 'GatePass' ]);
            Permission::create(['title' => 'View GatePass Tile', 'slug' => 'view_gatepass_tile' , 'method' => 'tile', 'model' => 'GatePass' ]);
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }


    // public static function superAdminPermissions()
    // {
    //     $superAdminPerm = Permission::all();
    //     return $superAdminPerm;
    // }

    /** Relationships */
    public function roles(){return $this->belongsToMany(Role::class, 'roles_permissions');}
    //  Permission can be given to multiple users
    // public function users(){return $this->belongsToMany(config('auth.providers.users.model') ,'users_permissions');}
    public function users(){return $this->belongsToMany(User::class ,'users_permissions');}
}
