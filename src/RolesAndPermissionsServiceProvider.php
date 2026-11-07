<?php

namespace Karacraft\RolesAndPermissions;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Karacraft\RolesAndPermissions\Http\Middleware\RoleMiddleware;

class RolesAndPermissionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/roles-and-permissions.php', 'roles-and-permissions');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        //  Register the `role` route middleware alias
        $this->app->make(Router::class)->aliasMiddleware('role', RoleMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }
    }

    protected function publishResources()
    {
        //  Publish Migrations
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'RolesAndPermissions-migrations');

        //  Publish Seeders
        $this->publishes([
            __DIR__ . '/../database/seeders/RolesAndPermissionsSeeder.php' => database_path('seeders/RolesAndPermissionsSeeder.php'),
        ], 'RolesAndPermissions-seeders');

        //  Publish Traits
        $this->publishes([
            __DIR__ . '/Traits/HasPermissionTrait.php' => app_path('Traits/HasPermissionTrait.php'),
        ], 'RolesAndPermissions-traits');

        //  Publish Config
        $this->publishes([
            __DIR__ . '/config/roles-and-permissions.php' => config_path('roles-and-permissions.php'),
        ], 'RolesAndPermissions-config');
    }
}
