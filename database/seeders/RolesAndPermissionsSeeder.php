<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Karacraft\RolesAndPermissions\Support\RolePermissionSeeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app(RolePermissionSeeder::class)->run();
    }
}
