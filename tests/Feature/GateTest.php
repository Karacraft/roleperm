<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Illuminate\Support\Facades\Gate;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Karacraft\RolesAndPermissions\Tests\TestCase;

class GateTest extends TestCase
{
    public function test_direct_permissions_are_authorized(): void
    {
        $user = $this->makeUser();
        $user->givePermissionTo(Permission::create(['title' => 'Create User']));

        $this->assertTrue($user->can('create_user'));
        $this->assertFalse($user->can('delete_user'));
    }

    public function test_role_permissions_are_authorized(): void
    {
        $user = $this->makeUser();

        $permission = Permission::create(['title' => 'Create User']);
        $role = Role::create(['title' => 'Admin']);
        $role->permissions()->attach($permission);
        $user->assignRole('admin');

        $this->assertTrue(Gate::forUser($user)->allows('create_user'));
    }

    public function test_unknown_abilities_fall_through_to_other_gates(): void
    {
        $user = $this->makeUser();

        $this->assertFalse($user->can('app-specific-ability'));

        Gate::define('app-specific-ability', fn () => true);

        $this->assertTrue($user->can('app-specific-ability'));
    }

    public function test_denies_guests(): void
    {
        Permission::create(['title' => 'Create User']);

        $this->assertFalse(Gate::allows('create_user'));
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);
    }
}
