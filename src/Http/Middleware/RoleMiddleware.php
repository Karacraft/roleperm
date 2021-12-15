<?php

namespace Karacraft\Roleperm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // $t = array();
        // foreach($roles as $role)
        // {
        //     array_push($t,$role);
        // }
        // dd($t);
        // dd($request);
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
