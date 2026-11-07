<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Tests\TestCase;

class ModelsTest extends TestCase
{
    public function test_slug_is_generated_from_title(): void
    {
        $role = Role::create(['title' => 'Super Admin']);

        $this->assertSame('Super Admin', $role->title);
        $this->assertSame('super_admin', $role->slug);
    }

    public function test_titles_and_slugs_are_normalized(): void
    {
        $method = Method::create(['title' => 'create']);

        $this->assertSame('Create', $method->title);
        $this->assertSame('create', $method->slug);

        $permission = Permission::create([
            'title' => 'edit user profile',
            'slug' => 'Edit User Profile!!',
            'model' => 'User',
            'method' => 'Edit',
        ]);

        $this->assertSame('Edit User Profile', $permission->title);
        $this->assertSame('edit_user_profile', $permission->slug);
    }

    public function test_guard_name_defaults_to_configured_guard(): void
    {
        $role = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'Create User']);

        $this->assertSame('web', $role->guard_name);
        $this->assertSame('web', $permission->guard_name);
    }

    public function test_models_use_configured_table_names(): void
    {
        config()->set('roles-and-permissions.tables.roles', 'custom_roles');
        config()->set('roles-and-permissions.tables.permissions', 'custom_permissions');
        config()->set('roles-and-permissions.tables.methods', 'custom_methods');

        $this->assertSame('custom_roles', (new Role())->getTable());
        $this->assertSame('custom_permissions', (new Permission())->getTable());
        $this->assertSame('custom_methods', (new Method())->getTable());
    }
}
