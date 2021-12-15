<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Karacraft\Roleperm\Models\Role;
use Karacraft\Roleperm\Models\Permission;

class OtherSeeder extends Seeder
{
    public function run()
    {
        $this->addRolesAndPermissions();
    }   

    
    public function addRolesAndPermissions()
    {
        $this->seedSuperUser();
        $this->seedUser();
        // $this->seedStoreManager();
    }

    public function seedSuperUser()
    {
        //  Permissions
        $superAdminPerm = Permission::all();
        //  Roles
        $superAdminRole = Role::find(1);
        $superAdminRole->permissions()->attach($superAdminPerm);
        //  SUPER ADMINS
        $user = User::find(1);
        $user->roles()->attach($superAdminRole);
        $user->permissions()->attach($superAdminPerm);
    }

    public function seedUser()
    {
        //  Simple User Permissions
        $matchModels = ['GatePass'];
        $matchMethods = ['create','edit','update','display','list','hold','tile'];
        $userPermissions = Permission::whereIn('model',$matchModels)->whereIn('method',$matchMethods)->get();
        // $this->command->info($userPermissions);
        //  Roles
        $userRole = Role::find(4);
        $userRole->permissions()->attach($userPermissions);
        //  User
        $user2 = User::find(2);
        $user2->roles()->attach($userRole);
        $user2->permissions()->attach($userPermissions);

        $user3 = User::find(3);
        $user3->roles()->attach($userRole);
        $user3->permissions()->attach($userPermissions);
    }

    public function seedStoreManager()
    {
        //  Simple User Permissions
        $matchModels = ['GatePass','Issuance','Item','Location','Mrfd','PurchaseRequest','Receive','Supplier'];
        $matchMethods = ['create','edit','update','view','list','approve','reject','cancel','hold','tile'];
        $userPermissions = Permission::whereIn('model',$matchModels)->whereIn('method',$matchMethods)->get();
        //  Roles
        $userRole = Role::find(2);
        $userRole->permissions()->attach($userPermissions);
        //  User
        $user3 = User::find(4);
        $user3->roles()->attach($userRole);
        $user3->permissions()->attach($userPermissions);
    }
}
