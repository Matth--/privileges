<?php

namespace MatthC\Privileges\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PrivilegesRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure|\Closure $next
     * @param $roles
     * @return mixed
     * @internal param null|string $guard
     */
    public function handle($request, Closure $next, $roles)
    {
        if(Auth::guest() || !$request->user()->hasRole(explode('|', $roles))) {
            abort(403);
        }

        return $next($request);
    }
}