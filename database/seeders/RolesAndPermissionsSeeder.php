<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    private $methods;

    public function run()
    {
        $this->methods = config('roles-and-permissions.permission-methods', 'permission-methods');

        $this->createBaseMethods();
        $this->createBasePermissions();
        $this->createBaseRoles();
        $this->createSuperAdmin();
        $this->seedSuperUser();
      
    }   

    public function createBaseMethods()
    {
        DB::beginTransaction();
        try {
            for ($i=0; $i < count($this->methods); $i++) { 
                Method::create(['title' => $this->methods[$i] , 'slug' =>$this->methods[$i]  ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createBasePermissions()
    {
        DB::beginTransaction();
        try {
            for ($i=0; $i < count($this->methods); $i++) { 
                Permission::create(['title' => $this->methods[$i] . ' User','slug' => $this->methods[$i] . ' User', 'method' => $this->methods[$i] , 'model' => 'User' ]);
                Permission::create(['title' => $this->methods[$i] . ' Permission','slug' => $this->methods[$i] . ' Permission', 'method' => $this->methods[$i], 'model' => 'Permission' ]);
                Permission::create(['title' => $this->methods[$i] . ' Role','slug' => $this->methods[$i] . ' Role', 'method' => $this->methods[$i] , 'model' => 'Role' ]);
                Permission::create(['title' => $this->methods[$i] . ' Method','slug' => $this->methods[$i] . ' Method', 'method' => $this->methods[$i] , 'model' => 'Method' ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createBaseRoles()
    {
        DB::beginTransaction();
        try {
            //  User Model
            Role::create(['title' => 'Super Admin','slug' => 'Super Admin', 'description' => 'Application Owner']);
            Role::create(['title' => 'User','slug' => 'User', 'description' => 'Restricted User']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createSuperAdmin()
    {
        DB::beginTransaction();
        try {
            //  User Model
            User::create([
                'name' => config('roles-and-permissions.user-info.name', 'name'),
                'email' => config('roles-and-permissions.user-info.email', 'email'),
                'password' => Hash::make(config('roles-and-permissions.user-info.password', 'password')),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function seedSuperUser()
    {
        //  Permissions
        $superAdminPermissions = Permission::all();
        //  Roles
        $superAdminRole = Role::where('slug','super_admin')->first();
        $superAdminRole->permissions()->attach($superAdminPermissions);
        //  SUPER ADMINS
        $user = User::find(1);
        $user->roles()->attach($superAdminRole);
        $user->permissions()->attach($superAdminPermissions);
    }


}