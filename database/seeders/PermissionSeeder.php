<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Karacraft\Roleperm\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::createBasePermissions();
    }
}
