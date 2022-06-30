<?php

namespace Karacraft\RolesAndPermissions;

use Exception;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Http\Kernel;       //  Must if we want our Global Middleware in Application
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Karacraft\RolesAndPermissions\Http\Middleware\RoleMiddleware;

class RolesAndPermissionsServiceProvider extends ServiceProvider
{
    
    public function register()
    {
        //
    }

    public function boot(Kernel $kernel)
    {
        /*************************-Apply Globallay-*****************/
        // $kernel->pushMiddleware(RoleMiddleware::class);  FIXME: THis is not allowing hasRole()

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');   //  Load Routes
        $this->loadViewsFrom(__DIR__.'/../resources/views','RolesAndPermissions'); //  Load Views
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');  //  Load Migrations - ENsure proper timestamps and no stub

        if( $this->app->runningInConsole()){
            $this->publishResources();
        }

        /*************************-Route Middleware-*****************/
        // $router = $this->app->make(Router::class);
        // $router->aliasMiddleware('role',RoleMiddleware::class);
    }

    protected function publishResources()
    {
        //  Publish Migrations via Stub
        $this->publishes([
            __DIR__ . '/../database/migrations/create_package_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_package_tables.php'),
        ],'RolesAndPermissions-migrations');

        //  Publish Seeders
        $this->publishes([
            __DIR__ . '/../database/seeders/RolesAndPermissionsSeeder.php' => database_path('seeders/RolesAndPermissionsSeeder.php'),
        ],'RolesAndPermissions-seeders');

        //  Publish Traits
        $this->publishes([
            __DIR__ . '/Traits/HasPermissionTrait.php'       => app_path('Traits/HasPermissionTrait.php'),
            __DIR__ . '/Traits/RolesAndPermissionsTrait.php' => app_path('Traits/RolesAndPermissionsTrait.php'),
        ],'RolesAndPermissions-traits');

        //  Publish Helpers
        // $this->publishes([
        //     __DIR__ . '/Helpers/Constants.php' => app_path('Helpers/Constants.php'),
        //     __DIR__ . '/Helpers/Misc.php' => app_path('Helpers/Misc.php'),
        //     __DIR__ . '/Helpers/SeederHelper.php' => app_path('Helpers/SeederHelper.php'),
        // ],'RolesAndPermissions-helpers');
        //  Publish Models
        $this->publishes([
            __DIR__ . '/Models/Base.php'        => app_path('Models/Base.php'),
            __DIR__ . '/Models/Permission.php'  => app_path('Models/Permission.php'),
            __DIR__ . '/Models/Role.php'        => app_path('Models/Role.php'),
        ],'RolesAndPermissions-models');
        //  Publish Config
        $this->publishes([
            __DIR__ . '/config/roles-and-permissions.php' => config_path('roles-and-permissions.php'),
        ], 'RolesAndPermissions-config');
    }
}
