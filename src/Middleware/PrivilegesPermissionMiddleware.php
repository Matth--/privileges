<?php

namespace MatthC\Privileges\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;

class PrivilegesPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure|\Closure $next
     * @param $permissions
     * @return mixed
     * @internal param $roles
     * @internal param null|string $guard
     */
    public function handle($request, Closure $next, $permissions)
    {
        if(Auth::guest() || !$request->user()->can(explode('|', $permissions))) {
            abort(403);
        }

        return $next($request);
    }
}