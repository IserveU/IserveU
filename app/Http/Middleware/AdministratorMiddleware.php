<?php

namespace App\Http\Middleware;
use Illuminate\Http\RedirectResponse;

use Closure;

class AdministratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if(! $request->user()->hasRole($role)) {
            return new RedirectResponse(url('/#/home'));
        }

        return $next($request);
    }
}
