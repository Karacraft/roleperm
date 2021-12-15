<?php

namespace Karacraft\Roleperm;

use Exception;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Http\Kernel;       //  Must if we want our Global Middleware in Application
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Karacraft\Roleperm\Http\Middleware\RoleMiddleware;

class RolePermServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/../resources/views','roleperm'); //  Load Views
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
        //  php artisan vendor:publish --provider="Karacraft\Roleperm\RolePermServiceProvider" --tag="roleperm-migrations"
        $this->publishes([
            __DIR__ . '/../database/migrations/create_package_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_package_tables.php'),
        ],'roleperm-migrations');

        //  Publish Seeders
        //  php artisan vendor:publish --provider="Karacraft\Roleperm\RolePermServiceProvider" --tag="roleperm-seeders"
        $this->publishes([
            __DIR__ . '/../database/seeders/RoleSeeder.php' => database_path('seeders/RoleSeeder.php'),
            __DIR__ . '/../database/seeders/PermissionSeeder.php' => database_path('seeders/PermissionSeeder.php'),
            __DIR__ . '/../database/seeders/OtherSeeder.php' => database_path('seeders/OtherSeeder.php'),
        ],'roleperm-seeders');
        //  Publish Traits
        $this->publishes([
            __DIR__ . '/Traits/HasPermission.php' => app_path('Traits/HasPermission.php'),
            __DIR__ . '/Traits/RolePermission.php' => app_path('Traits/RolePermission.php'),
        ],'roleperm-traits');
        //  Publish Helpers
        $this->publishes([
            __DIR__ . '/Helpers/Constants.php' => app_path('Helpers/Constants.php'),
            __DIR__ . '/Helpers/Misc.php' => app_path('Helpers/Misc.php'),
            __DIR__ . '/Helpers/SeederHelper.php' => app_path('Helpers/SeederHelper.php'),
        ],'releperm-helpers');
    }
}
