<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Support\RolePermissionSeeder;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Karacraft\RolesAndPermissions\Tests\TestCase;
use RuntimeException;

class SeederTest extends TestCase
{
    public function test_it_seeds_methods_permissions_and_roles(): void
    {
        $this->app->make(RolePermissionSeeder::class)->run();

        $this->assertSame(12, Method::count());
        $this->assertSame(48, Permission::count());
        $this->assertDatabaseHas('roles', ['slug' => 'super_admin', 'guard_name' => 'web']);
        $this->assertDatabaseHas('roles', ['slug' => 'user', 'guard_name' => 'web']);
    }

    public function test_it_does_not_create_a_user_by_default(): void
    {
        $this->app->make(RolePermissionSeeder::class)->run();

        $this->assertSame(0, User::count());
    }

    public function test_it_is_idempotent(): void
    {
        $seeder = $this->app->make(RolePermissionSeeder::class);

        $seeder->run();
        $counts = [Method::count(), Permission::count(), Role::count()];

        $seeder->run();

        $this->assertSame($counts, [Method::count(), Permission::count(), Role::count()]);
    }

    public function test_it_seeds_the_super_admin_when_enabled(): void
    {
        config()->set('roles-and-permissions.seed.super_admin.enabled', true);
        config()->set('roles-and-permissions.seed.super_admin.name', 'Owner');
        config()->set('roles-and-permissions.seed.super_admin.email', 'owner@example.com');
        config()->set('roles-and-permissions.seed.super_admin.password', 'secret-password');

        $this->app->make(RolePermissionSeeder::class)->run();

        $user = User::where('email', 'owner@example.com')->firstOrFail();

        $this->assertSame('Owner', $user->name);
        $this->assertTrue(Hash::check('secret-password', $user->password));
        $this->assertTrue($user->hasRole('super_admin'));
        $this->assertTrue($user->hasPermissionTo('create_user'));
    }

    public function test_it_throws_when_super_admin_is_enabled_without_credentials(): void
    {
        config()->set('roles-and-permissions.seed.super_admin.enabled', true);
        config()->set('roles-and-permissions.seed.super_admin.email', null);
        config()->set('roles-and-permissions.seed.super_admin.password', null);

        $this->expectException(RuntimeException::class);

        $this->app->make(RolePermissionSeeder::class)->run();
    }
}
