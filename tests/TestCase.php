<?php

namespace Karacraft\RolesAndPermissions\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Karacraft\RolesAndPermissions\PermissionsServiceProvider;
use Karacraft\RolesAndPermissions\RolesAndPermissionsServiceProvider;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            RolesAndPermissionsServiceProvider::class,
            PermissionsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        $app['config']->set('cache.default', 'array');

        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('roles-and-permissions.user_model', User::class);
        $app['config']->set('roles-and-permissions.guards', ['web']);
        $app['config']->set('roles-and-permissions.default_guard', 'web');

        $app['config']->set('roles-and-permissions.seed.super_admin.enabled', false);
        $app['config']->set('roles-and-permissions.seed.super_admin.email', null);
        $app['config']->set('roles-and-permissions.seed.super_admin.password', null);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
