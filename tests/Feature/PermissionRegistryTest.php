<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Support\Config;
use Karacraft\RolesAndPermissions\Support\PermissionRegistry;
use Karacraft\RolesAndPermissions\Tests\TestCase;

class PermissionRegistryTest extends TestCase
{
    public function test_it_lazily_resolves_permission_slugs(): void
    {
        $registry = $this->app->make(PermissionRegistry::class);

        $this->assertFalse($registry->contains('create_user'));

        Permission::create(['title' => 'Create User']);

        $this->assertTrue($registry->contains('create_user'));
    }

    public function test_it_stores_slugs_in_the_cache(): void
    {
        Permission::create(['title' => 'Create User']);
        Permission::create(['title' => 'Update User']);

        $this->app->make(PermissionRegistry::class)->slugs();

        $this->assertEqualsCanonicalizing(
            ['create_user', 'update_user'],
            Cache::get(Config::cacheKey())
        );
    }

    public function test_creating_or_deleting_a_permission_flushes_the_cache(): void
    {
        $registry = $this->app->make(PermissionRegistry::class);

        $permission = Permission::create(['title' => 'Create User']);
        $registry->slugs();

        $this->assertTrue($registry->contains('create_user'));

        $permission->delete();

        $this->assertFalse(Cache::has(Config::cacheKey()));
        $this->assertFalse($registry->contains('create_user'));
    }

    public function test_caching_can_be_disabled(): void
    {
        config()->set('roles-and-permissions.cache.enabled', false);
        $this->app->forgetInstance(PermissionRegistry::class);

        Permission::create(['title' => 'Create User']);

        $registry = $this->app->make(PermissionRegistry::class);

        $this->assertTrue($registry->contains('create_user'));
        $this->assertFalse(Cache::has(Config::cacheKey()));
    }
}
