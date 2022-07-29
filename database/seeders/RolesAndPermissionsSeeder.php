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
    private $models;

    public function run()
    {
        $this->methods = config('roles-and-permissions.permission-methods', 'permission-methods');
        $this->models = config('roles-and-permissions.models','models');

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
            for ($j=0; $j < count($this->models); $j++){
                for ($i=0; $i < count($this->methods); $i++) { 
                    Permission::create(['title' => $this->methods[$i] . " " . $this->models[$j],'slug' => $this->methods[$i] . " " . $this->models[$j], 'method' => $this->methods[$i] , 'model' => $this->models[$j] ]);
                }
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
                'name' => config('roles-and-permissions.user-info-1.name', 'name'),
                'email' => config('roles-and-permissions.user-info-1.email', 'email'),
                'password' => Hash::make(config('roles-and-permissions.user-info-1.password', 'password')),
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
        $user1 = User::find(1);
        $user1->roles()->attach($superAdminRole);
        $user1->permissions()->attach($superAdminPermissions);
    }


}