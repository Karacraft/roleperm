<?php

namespace Karacraft\RolesAndPermissions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //  This protects , when applied on Routes 
        if(!$request->user()->hasRole($role)) {
            abort(403, "You are not authorized");
        }

        //  This protects , when you don't have the permission in Controller
        if($permission !== null && !$request->user()->can($permission)) {
            abort(403,"You are not authorized : PERMISSION $permission");
        }

        return $next($request);
    }
}
