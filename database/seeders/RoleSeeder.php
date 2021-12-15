<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Karacraft\Roleperm\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::createBaseRoles();
    }
}
