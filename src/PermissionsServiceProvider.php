<?php

namespace Karacraft\RolesAndPermissions;

use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Karacraft\RolesAndPermissions\Models\Permission;

class PermissionsServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (Exception $e) {
            report($e);
            return false;
        }

        /** This is a blade directive, to show number in currency */
        // Learn More https://codelike.pro/how-to-create-custom-blade-directives-in-laravel-5/
        Blade::directive('tocurrency', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
        //  DEV-COM: These will be shared across all view
        //  Learn More https://laravel.com/docs/5.8/views#sharing-data-with-all-views
        //  View::share('username','anything'); // This doesn't support Auth()
        // LogActivity::observe(LogObserver::class);
        // Learn More: https://stackoverflow.com/a/60726871/4853427
        //  WORKING:
        Blade::if('roles', function (...$role) {
            if(auth()->user()->hasRole(...$role))
                return true;
            return false;
        });
        //  FIXME:
        // Blade::if('user', function () {
        //     if(auth()->user()->hasRole('user'))
        //         return true;
        //     return false;
        // });
        // Blade::if('store_manager', function () {
        //     if(auth()->user()->hasRole('store_manager'))
        //         return true;
        //     return false;
        // });
        //Blade directives 
        Blade::directive('role', function ($role) 
        { 
            return "if(auth()->check() && auth()->user()->hasRole({$role})) :"; //return this ifstatement inside php tag 
        });
        Blade::directive('endrole', function ($role) 
        { 
            return "endif;"; //return this endif statement inside php tag
        });
    }
}
