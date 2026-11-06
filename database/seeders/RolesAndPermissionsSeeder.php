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
        $this->methods = config('roles-and-permissions.permission-methods', []);
        $this->models = config('roles-and-permissions.models', []);

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
            foreach ($this->methods as $method) {
                Method::updateOrCreate(
                    ['title' => $method],
                    ['slug' => $method]
                );
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
            foreach ($this->models as $model) {
                foreach ($this->methods as $method) {
                    $title = $method . " " . $model;

                    Permission::updateOrCreate(
                        ['title' => $title],
                        ['slug' => $title, 'method' => $method, 'model' => $model]
                    );
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
            Role::updateOrCreate(
                ['title' => 'Super Admin'],
                ['slug' => 'Super Admin', 'description' => 'Application Owner']
            );
            Role::updateOrCreate(
                ['title' => 'User'],
                ['slug' => 'User', 'description' => 'Restricted User']
            );
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
            User::updateOrCreate(
                ['email' => config('roles-and-permissions.user-info-1.email', 'email')],
                [
                    'name' => config('roles-and-permissions.user-info-1.name', 'name'),
                    'password' => Hash::make(config('roles-and-permissions.user-info-1.password', 'password')),
                ]
            );
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function seedSuperUser()
    {
        $superAdminRole = Role::where('slug', 'super_admin')->first();

        if ($superAdminRole === null) {
            return;
        }

        //  Permissions
        $superAdminPermissions = Permission::all();

        $superAdminRole->permissions()->syncWithoutDetaching($superAdminPermissions->modelKeys());

        //  SUPER ADMINS
        $user = User::where('email', config('roles-and-permissions.user-info-1.email', 'email'))->first();

        if ($user === null) {
            return;
        }

        $user->roles()->syncWithoutDetaching([$superAdminRole->getKey()]);
        $user->permissions()->syncWithoutDetaching($superAdminPermissions->modelKeys());
    }


}
