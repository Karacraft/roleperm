<?php

namespace Karacraft\RolesAndPermissions;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Karacraft\RolesAndPermissions\Console\Commands\CachePermissions;
use Karacraft\RolesAndPermissions\Console\Commands\ClearPermissions;
use Karacraft\RolesAndPermissions\Console\Commands\SeedRolesAndPermissions;
use Karacraft\RolesAndPermissions\Support\Config;
use Karacraft\RolesAndPermissions\Support\PermissionRegistry;

class PermissionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PermissionRegistry::class, function ($app) {
            $store = Config::cacheStore();

            $repository = $store
                ? $app['cache']->store($store)
                : $app['cache']->store();

            return new PermissionRegistry(
                $repository,
                Config::cacheEnabled(),
                Config::cacheKey(),
                Config::cacheTtl(),
            );
        });
    }

    public function boot()
    {
        $registry = $this->app->make(PermissionRegistry::class);

        Gate::before(function ($user, $ability) use ($registry) {
            if ($user === null || ! $registry->contains($ability)) {
                return null;
            }

            return $user->hasPermissionTo($ability) ? true : null;
        });

        Blade::directive('tocurrency', function ($money) {
            return "<?php echo number_format({$money}, \\Karacraft\\RolesAndPermissions\\Support\\Config::currencyDecimals()); ?>";
        });

        Blade::if('roles', function (...$role) {
            return auth()->check() && auth()->user()->hasRole(...$role);
        });

        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CachePermissions::class,
                ClearPermissions::class,
                SeedRolesAndPermissions::class,
            ]);
        }
    }
}
