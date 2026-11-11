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
        // Migrations are loaded automatically. They are intentionally not
        // publishable, otherwise a published copy would run alongside the
        // package copy and attempt to create the same tables twice.
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'roles-and-permissions');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'roles-and-permissions');

        //  Register the `role` route middleware alias
        $this->app->make(Router::class)->aliasMiddleware('role', RoleMiddleware::class);

        if ($this->uiIsAvailable()) {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }
    }

    protected function uiIsAvailable(): bool
    {
        return (bool) config('roles-and-permissions.ui.enabled', false)
            && class_exists(\Livewire\LivewireServiceProvider::class)
            && class_exists(\Livewire\Component::class);
    }

    protected function publishResources()
    {
        //  Publish Seeders
        $this->publishes([
            __DIR__ . '/../database/seeders/RolesAndPermissionsSeeder.php' => database_path('seeders/RolesAndPermissionsSeeder.php'),
        ], 'RolesAndPermissions-seeders');

        //  Publish Traits
        $this->publishes([
            __DIR__ . '/Traits/HasPermissionTrait.php' => app_path('Traits/HasPermissionTrait.php'),
        ], 'RolesAndPermissions-traits');

        //  Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/roles-and-permissions'),
        ], 'RolesAndPermissions-views');

        //  Publish Translations
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/roles-and-permissions'),
        ], 'RolesAndPermissions-lang');

        //  Publish Config
        $this->publishes([
            __DIR__ . '/config/roles-and-permissions.php' => config_path('roles-and-permissions.php'),
        ], 'RolesAndPermissions-config');
    }
}
