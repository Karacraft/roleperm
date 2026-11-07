<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Support\Config;
use Karacraft\RolesAndPermissions\Tests\TestCase;

class CommandsTest extends TestCase
{
    public function test_seed_command_seeds_roles_and_permissions(): void
    {
        $this->artisan('roleperm:seed')->assertSuccessful();

        $this->assertDatabaseHas('roles', ['slug' => 'super_admin']);
        $this->assertSame(48, Permission::count());
    }

    public function test_seed_command_creates_super_admin_when_flag_is_used(): void
    {
        config()->set('roles-and-permissions.seed.super_admin.email', 'admin@example.com');
        config()->set('roles-and-permissions.seed.super_admin.password', 'secret-password');

        $this->artisan('roleperm:seed', ['--super-admin' => true])->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
    }

    public function test_seed_command_refuses_to_run_in_production_without_force(): void
    {
        $this->app['env'] = 'production';

        $this->artisan('roleperm:seed')->assertFailed();
    }

    public function test_seed_command_runs_in_production_with_force(): void
    {
        $this->app['env'] = 'production';

        $this->artisan('roleperm:seed', ['--force' => true])->assertSuccessful();
    }

    public function test_cache_and_clear_commands(): void
    {
        Permission::create(['title' => 'Create User']);

        $this->artisan('roleperm:cache')->assertSuccessful();
        $this->assertTrue(Cache::has(Config::cacheKey()));

        $this->artisan('roleperm:clear')->assertSuccessful();
        $this->assertFalse(Cache::has(Config::cacheKey()));
    }
}
