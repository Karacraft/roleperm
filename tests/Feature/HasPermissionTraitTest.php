<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Karacraft\RolesAndPermissions\Tests\TestCase;

class HasPermissionTraitTest extends TestCase
{
    protected User $user;
    protected Role $admin;
    protected Permission $createUser;
    protected Permission $updateUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $this->admin = Role::create(['title' => 'Admin']);

        $this->createUser = Permission::create([
            'title' => 'Create User',
            'model' => 'User',
            'method' => 'Create',
        ]);

        $this->updateUser = Permission::create([
            'title' => 'Update User',
            'model' => 'User',
            'method' => 'Update',
        ]);

        $this->admin->permissions()->attach($this->createUser);
    }

    public function test_roles_can_be_assigned_and_removed(): void
    {
        $this->user->assignRole('admin');

        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertTrue($this->user->hasRole('Admin'));
        $this->assertTrue($this->user->hasAnyRole('user', 'admin'));
        $this->assertFalse($this->user->hasAnyRole('user'));

        $this->user->removeRole('admin');

        $this->assertFalse($this->user->hasRole('admin'));
    }

    public function test_assigning_the_same_role_twice_does_not_duplicate_it(): void
    {
        $this->user->assignRole('admin');
        $this->user->assignRole('admin');

        $this->assertCount(1, $this->user->roles);
    }

    public function test_permissions_can_be_given_directly(): void
    {
        $this->user->givePermissionTo('update_user');

        $this->assertTrue($this->user->hasPermissionTo('update_user'));
        $this->assertTrue($this->user->hasPermissionTo($this->updateUser));

        $this->user->revokePermissionTo('update_user');

        $this->assertFalse($this->user->hasPermissionTo('update_user'));
    }

    public function test_permissions_are_inherited_from_roles(): void
    {
        $this->user->assignRole('admin');

        $this->assertTrue($this->user->hasPermissionTo('create_user'));
        $this->assertTrue($this->user->hasPermissionThroughRole('create_user'));
        $this->assertFalse($this->user->hasPermissionTo('update_user'));
    }

    public function test_permissions_can_be_replaced(): void
    {
        $this->user->givePermissionTo('create_user');
        $this->user->refreshPermissions('update_user');

        $this->assertFalse($this->user->hasPermissionTo('create_user'));
        $this->assertTrue($this->user->hasPermissionTo('update_user'));
    }

    public function test_unknown_permissions_are_denied(): void
    {
        $this->assertFalse($this->user->hasPermissionTo('does_not_exist'));
    }

    public function test_permission_can_be_resolved_by_id(): void
    {
        $this->user->givePermissionTo($this->updateUser->getKey());

        $this->assertTrue($this->user->hasPermissionTo($this->updateUser->getKey()));
    }
}
