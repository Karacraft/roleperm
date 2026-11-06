<?php

namespace Karacraft\RolesAndPermissions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role, $permission = null)
    {
        $roles = array_filter(array_map('trim', preg_split('/[|,]/', (string) $role)));
        $user = $request->user();

        //  This protects , when applied on Routes
        if (! $user || ! $user->hasAnyRole($roles)) {
            abort(403, "You are not authorized");
        }

        //  This protects , when you don't have the permission in Controller
        if($permission !== null && ! $user->can($permission)) {
            abort(403,"You do not have access permission -- $permission");
        }

        return $next($request);
    }
}
